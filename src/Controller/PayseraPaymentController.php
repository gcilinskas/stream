<?php

namespace App\Controller;

use App\Entity\Log;
use App\Entity\Movie;
use App\Entity\PayseraPayment;
use App\Factory\TicketFactory;
use App\Service\LogService;
use App\Service\MovieService;
use App\Service\PayseraPaymentService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WebToPay;
use WebToPayException;

/**
 * Class PayseraPaymentController
 *
 * @Route("/paysera")
 */
class PayseraPaymentController extends AbstractController
{
    /**
     * @var PayseraPaymentService
     */
    private $payseraPaymentService;

    /**
     * @var MovieService
     */
    private $movieService;

    /**
     * @var TicketFactory
     */
    private $ticketFactory;

    /**
     * @var LogService
     */
    private $logService;

    /**
     * PayseraPaymentController constructor.
     *
     * @param PayseraPaymentService $payseraPaymentService
     * @param MovieService $movieService
     * @param TicketFactory $ticketFactory
     * @param LogService $logService
     */
    public function __construct(
        PayseraPaymentService $payseraPaymentService,
        MovieService $movieService,
        TicketFactory $ticketFactory,
        LogService $logService
    ) {
        $this->payseraPaymentService = $payseraPaymentService;
        $this->movieService = $movieService;
        $this->ticketFactory = $ticketFactory;
        $this->logService = $logService;
    }

    /**
     * @Route("/new/{movie}")
     * @param Movie $movie
     *
     * @return Response
     * @throws Exception
     */
    public function new(Movie $movie): Response
    {
        if ($movie && $movie->isValidForPurchase($this->getUser())) {
            $payseraPayment = (new PayseraPayment())->setMovie($movie)
                ->setPrice($movie->getActivePriceByUser($this->getUser()))
                ->setUser($this->getUser())
                ->setStatus(PayseraPayment::STATUS_NOT_PAID);

            /** @var PayseraPayment $payseraPayment */
            $payseraPayment = $this->payseraPaymentService->create($payseraPayment);

            $log = (new Log())->setType(Log::TYPE_PAYSERA_NEW)
                ->setInfo(
                    sprintf('User ID %s opened paysera for a movie ID %s and created NOT_PAID payment ID = %s',
                        $this->getUser()->getId(),
                        $movie->getId(),
                        $payseraPayment->getId()
                    )
                );
            $this->logService->create($log);

            try {
                $response = $this->payseraPaymentService->pay($payseraPayment);

                return $this->json($response);
            } catch (Exception $e ) {
                return new Response($e->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }

        return $this->json('Filmas nera galimas pirkimui. Susisiekite su administracija', 400);
    }

    /**
     * @Route("/cancel/{payseraPayment}")
     * @param PayseraPayment $payseraPayment
     * @param Request $request
     *
     * @return Response
     * @throws Exception
     */
    public function cancelPayment(PayseraPayment $payseraPayment, Request $request): Response
    {
        $log = (new Log())->setType(Log::TYPE_PAYSERA_CANCEL)
            ->setInfo('PAYMENT ID = ' . $payseraPayment->getId());
        $this->logService->create($log);

        $data = $request->get('data');
        $payseraPayment->setStatus(PayseraPayment::STATUS_CANCELED)->setToken($data);
        $this->payseraPaymentService->update($payseraPayment);

        return new RedirectResponse($this->generateUrl('home_index'));
    }

    /**
     * @Route("/success/{payseraPayment}")
     * @param PayseraPayment $payseraPayment
     * @param Request $request
     *
     * @return Response
     * @throws Exception
     */
    public function success(PayseraPayment $payseraPayment, Request $request): Response
    {
        $log = (new Log())->setType(Log::TYPE_PAYSERA_SUCCESS);
        $this->logService->create($log);

        $data = $request->get('data');
        $payseraPayment->setStatus(PayseraPayment::STATUS_PAID)->setToken($data);
        $this->payseraPaymentService->update($payseraPayment);
        $this->ticketFactory->createForPayment($payseraPayment);

        $log->setInfo('Paysera payment ID' . $payseraPayment->getId())->setStatus(Log::STATUS_OK);
        $this->logService->update($log);

        return $this->redirectToRoute('app_ticket_index');
    }

    /**
     * @Route("/callback/{payseraPayment}")
     * @param PayseraPayment $payseraPayment
     * @param Request $request
     *
     * @return Response
     * @throws Exception
     */
    public function callback(PayseraPayment $payseraPayment, Request $request): Response
    {
        $log = (new Log())->setType(Log::TYPE_PAYSERA_CALLBACK)->setRequest(json_encode($request->query->all()));
        $this->logService->create($log);

        try {
            $response = WebToPay::checkResponse($_GET, [
                'projectid' => $_ENV['PAYSERA_PROJECT_ID'],
                'sign_password' => $_ENV['PAYSERA_SECRET_KEY'],
            ]);
        } catch (Exception $e) {
            $log->setStatus(Log::STATUS_NOK)
                ->setResponse($e->getMessage())
                ->setInfo('WebToPay::checkResponse failed');
            $this->logService->update($log);

            return new Response($e->getMessage());
        }

        try {
            $paymentSuccessful = $this->payseraPaymentService->isCallbackPaymentSuccessful(
                $payseraPayment,
                $response['status'],
                $response['orderid']
            );

            if ($paymentSuccessful) {
                $log->setStatus(Log::STATUS_OK)->setResponse(json_encode($response));
                $this->logService->update($log);

                return new Response('OK');
            } else {
                $log->setStatus(Log::STATUS_NOK)->setResponse(json_encode($response));
                $this->logService->update($log);

                return new Response('');
            }

        } catch (Exception $e) {
            $log->setStatus(Log::STATUS_NOK)->setResponse($e->getMessage())->setInfo(json_encode($response));
            $this->logService->update($log);

            return new Response($e->getMessage());
        }
    }
}

