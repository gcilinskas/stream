<?php

namespace App\Service;

use App\Entity\Log;
use App\Entity\Movie;
use App\Entity\PayseraPayment;
use App\Entity\User;
use App\Factory\PayseraPaymentFactory;
use App\Factory\SubscriptionFactory;
use App\Factory\TicketFactory;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use WebToPay;
use WebToPayException;

/**
 * Class PayseraPaymentService
 */
class PayseraPaymentService extends BaseService
{
    /**
     * @var LogService
     */
    private $logService;

    /**
     * @var TicketFactory
     */
    private $ticketFactory;

    /**
     * @var PayseraPaymentFactory
     */
    private $payseraPaymentFactory;

    /**
     * @var SubscriptionFactory
     */
    private $subscriptionFactory;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * PayseraPaymentService constructor.
     *
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @param LogService $logService
     * @param TicketFactory $ticketFactory
     * @param PayseraPaymentFactory $payseraPaymentFactory
     * @param SubscriptionFactory $subscriptionFactory
     * @param UserService $userService
     */
    public function __construct(
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        LogService $logService,
        TicketFactory $ticketFactory,
        PayseraPaymentFactory $payseraPaymentFactory,
        SubscriptionFactory $subscriptionFactory,
        UserService $userService
    ) {
        parent::__construct($em, $dispatcher);
        $this->logService = $logService;
        $this->ticketFactory = $ticketFactory;
        $this->payseraPaymentFactory = $payseraPaymentFactory;
        $this->subscriptionFactory = $subscriptionFactory;
        $this->userService = $userService;
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return PayseraPayment::class;
    }

    /**
     * @param PayseraPayment $entity
     * @param bool $flush
     *
     * @return mixed
     * @throws Exception
     */
    public function update($entity, bool $flush = true)
    {
        $entity->setUpdateAt(new DateTime());

        return parent::update($entity, $flush);
    }

    /**
     * @param PayseraPayment $payseraPayment
     *
     * @return array
     * @throws WebToPayException
     */
    public function pay(PayseraPayment $payseraPayment): array
    {
        $request = WebToPay::buildRequest(
            [
                'projectid' => $_ENV['PAYSERA_PROJECT_ID'],
                'sign_password' => $_ENV['PAYSERA_SECRET_KEY'],
                'orderid' => $payseraPayment->getId(),
                'amount' => $payseraPayment->getPrice()->getAmount(),
                'currency' => 'EUR',
                'country' => 'LT',
                'accepturl'=> $_ENV['DOMAIN'] . '/paysera/success/' . $payseraPayment->getId(),
                'cancelurl' => $_ENV['DOMAIN'] . '/paysera/cancel/' . $payseraPayment->getId(),
                'callbackurl' => $_ENV['DOMAIN'] . '/paysera/callback/' . $payseraPayment->getId(),
                'test' => $_ENV['PAYSERA_TEST'],
                'p_firstname' => $payseraPayment->getUser()->getFirstName(),
                'p_lastname' => $payseraPayment->getUser()->getLastName(),
            ]
        );

        return ["url" => WebToPay::PAYSERA_PAY_URL . '?data=' . $request['data'] . '&amp;sign=' . $request['sign'] . '&lng=lit'];
    }

    /**
     * @param PayseraPayment $payseraPayment
     * @param $data
     *
     * @return array
     * @throws Exception
     */
    public function handleCallbackPayment(PayseraPayment $payseraPayment, $data): array
    {
        $response = WebToPay::checkResponse($data, [
            'projectid' => $_ENV['PAYSERA_PROJECT_ID'],
            'sign_password' => $_ENV['PAYSERA_SECRET_KEY'],
        ]);

        if ($payseraPayment->getPrice()->getAmount() != $response['amount']) {
            throw new Exception(
                sprintf(
                    'Paysera Payment with ID %s prices do not match. %s is not equal to %s',
                    $payseraPayment->getId(),
                    $payseraPayment->getPrice()->getAmount(),
                    $response['amount']
                ),
                400
            );
        }

        $this->handleSuccessfulPayment($payseraPayment);

        return $response;
    }

    /**
     * @param PayseraPayment $payseraPayment
     *
     * @throws Exception
     */
    public function handleSuccessfulPayment(PayseraPayment $payseraPayment)
    {
        $payseraPayment->setStatus(PayseraPayment::STATUS_PAID);
        $this->update($payseraPayment);

        switch ($payseraPayment->getType()) {
            case PayseraPayment::TYPE_TICKET:
                $this->ticketFactory->createForPayment($payseraPayment);
                break;
            case PayseraPayment::TYPE_SUBSCRIPTION:
                $this->subscriptionFactory->upgradeByPayment($payseraPayment);
                $this->userService->setClubRole($payseraPayment->getUser());
                break;
        }
    }

    /**
     * @param Movie $movie
     * @param User $user
     *
     * @return array
     * @throws Exception
     */
    public function handleMoviePayment(Movie $movie, User $user): array
    {
        $payseraPayment = $this->payseraPaymentFactory->createNotPaidMoviePayment($movie, $user);

        $log = (new Log())->setType(Log::TYPE_PAYSERA_NEW)
            ->setInfo(
                sprintf('User ID %s opened paysera for a movie ID %s and created NOT_PAID payment ID = %s',
                    $user->getId(),
                    $movie->getId(),
                    $payseraPayment->getId()
                )
            );
        $this->logService->create($log);

        return $this->pay($payseraPayment);
    }

    /**
     * @param User $user
     *
     * @return array
     * @throws Exception
     */
    public function handleSubscriptionPayment(User $user): array
    {
        $payseraPayment = $this->payseraPaymentFactory->createNotPaidSubscriptionPayment($user);

        $log = (new Log())->setType(Log::TYPE_PAYSERA_NEW)->setInfo(
            'subscription payment. User = ' . $user->getId() . ' payment = ' . $payseraPayment->getId()
        );

        $this->logService->create($log);

        return $this->pay($payseraPayment);
    }
}
