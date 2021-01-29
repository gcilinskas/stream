<?php

namespace App\Service;

use App\Entity\PayseraPayment;
use App\Factory\TicketFactory;
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
     * PayseraPaymentService constructor.
     *
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @param LogService $logService
     * @param TicketFactory $ticketFactory
     */
    public function __construct(
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        LogService $logService,
        TicketFactory $ticketFactory
    ) {
        parent::__construct($em, $dispatcher);
        $this->logService = $logService;
        $this->ticketFactory = $ticketFactory;
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return PayseraPayment::class;
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
     * @param $status
     * @param $orderId
     *
     * @return bool
     * @throws Exception
     */
    public function isCallbackPaymentSuccessful(PayseraPayment $payseraPayment, $status, $orderId): bool
    {
        if ($status != 0 && $orderId) {
            $payseraPayment->setStatus(PayseraPayment::STATUS_PAID)
                ->setPayseraStatus($status)
                ->setPayseraOrderId($orderId);

            $this->update($payseraPayment);
            $this->ticketFactory->createForPayment($payseraPayment);

            return true;
        } else {
            $payseraPayment->setPayseraStatus($status)->setPayseraOrderId($orderId);

            return false;
        }
    }
}
