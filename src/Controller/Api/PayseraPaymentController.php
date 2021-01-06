<?php

namespace App\Controller\Api;

use App\Entity\Log;
use App\Entity\Movie;
use App\Entity\PayseraPayment;
use App\Factory\TicketFactory;
use App\Service\LogService;
use App\Service\MovieService;
use App\Service\PayseraPaymentService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WebToPay;

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

            $payseraPayment = $this->payseraPaymentService->create($payseraPayment);

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
        $data = $request->get('data');
        $payseraPayment->setStatus(PayseraPayment::STATUS_CANCELED)->setToken($data);
        $this->payseraPaymentService->update($payseraPayment);

        return $this->json(['response' => PayseraPayment::STATUS_CANCELED], 200);
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
        $data = $request->get('data');
        $payseraPayment->setStatus(PayseraPayment::STATUS_PAID)->setToken($data);
        $this->payseraPaymentService->update($payseraPayment);
        $this->ticketFactory->createForPayment($payseraPayment);

        return $this->redirectToRoute('app_ticket_index');
    }

    /**
     * @Route("/callback")
     * @param PayseraPayment $payseraPayment
     * @param Request $request
     *
     * @return Response
     * @throws Exception
     */
    public function callback(PayseraPayment $payseraPayment, Request $request): Response
    {
        $log = (new Log())->setType(Log::TYPE_PAYSERA_CALLBACK);
        $this->logService->create($log);

        try {
            $response = WebToPay::checkResponse($_GET, [
                'project_id' => $_ENV['PAYSERA_PROJECT_ID'],
                'sign_password' => $_ENV['PAYSERA_SECRET_KEY'],
            ]);

            if ($response['status'] != 0 && !empty($response['orderid'])) {
                $payseraPayment->setStatus(PayseraPayment::STATUS_PAID)
                    ->setPayseraStatus($response['status'])
                    ->setPayseraOrderId($response['orderid']);

                $this->payseraPaymentService->update($payseraPayment);
                $this->ticketFactory->createForPayment($payseraPayment);

                $log->setStatus(Log::STATUS_OK)->setInfo(json_encode($response));
                $this->logService->update($log);

                return new Response('OK');
            } else {
                $payseraPayment->setPayseraStatus($response['status'])
                    ->setPayseraError($response['error'])
                    ->setPayseraOrderId($response['orderid']);

                $log->setStatus(Log::STATUS_NOK)->setInfo(json_encode($response));
                $this->logService->update($log);
                
                return new Response('OK');
            }

        } catch (Exception $e) {
            $log->setStatus(Log::STATUS_NOK)->setInfo($e->getMessage());
            $this->logService->update($log);

            return new Response($e->getMessage(), 400);
        }
    }
}

