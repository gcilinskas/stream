<?php

namespace App\Controller\Api;

use App\Entity\Movie;
use App\Entity\PayseraPayment;
use App\Entity\Ticket;
use App\Factory\TicketFactory;
use App\Service\MovieService;
use App\Service\PayseraPaymentService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
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
     * PayseraPaymentController constructor.
     *
     * @param PayseraPaymentService $payseraPaymentService
     * @param MovieService $movieService
     * @param TicketFactory $ticketFactory
     */
    public function __construct(
        PayseraPaymentService $payseraPaymentService,
        MovieService $movieService,
        TicketFactory $ticketFactory
    ) {
        $this->payseraPaymentService = $payseraPaymentService;
        $this->movieService = $movieService;
        $this->ticketFactory = $ticketFactory;
    }

    /**
     * @Route("/new/{movie}")
     * @param Movie $movie
     *
     * @return Response
     * @throws Exception
     */
    public function new(Movie $movie)
    {
        if ($movie && $movie->isValidForPurchase()) {
            $payseraPayment = (new PayseraPayment())->setMovie($movie)
                ->setPrice($movie->getActivePrice())
                ->setUser($this->getUser())
                ->setStatus(PayseraPayment::STATUS_NOT_PAID);
            $payseraPayment = $this->payseraPaymentService->create($payseraPayment);

            try {
                $response = $this->payseraPaymentService->pay($payseraPayment);
                return $this->json($response, Response::HTTP_OK);
            } catch ( WebToPayException $e ) {
                return new Response($e->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }

        return $this->json(['errors' => [], 400]);
    }

    /**
     * @Route("/cancel/{payseraPayment}")
     * @param PayseraPayment $payseraPayment
     * @param Request $request
     *
     * @return Response
     * @throws Exception
     */
    public function cancelPayment(PayseraPayment $payseraPayment, Request $request)
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
    public function success(PayseraPayment $payseraPayment, Request $request)
    {
        $data = $request->get('data');
        $payseraPayment->setStatus(PayseraPayment::STATUS_PAID)->setToken($data);
        $this->payseraPaymentService->update($payseraPayment);
        $this->ticketFactory->createForPayment($payseraPayment);

        return $this->redirectToRoute('app_ticket_index');
    }
}
