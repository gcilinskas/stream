<?php

namespace App\Controller;

use App\Entity\Log;
use App\Entity\Movie;
use App\Entity\PayseraPayment;
use App\Service\LogService;
use App\Service\MovieService;
use App\Service\PayseraPaymentService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @var LogService
     */
    private $logService;

    /**
     * PayseraPaymentController constructor.
     *
     * @param PayseraPaymentService $payseraPaymentService
     * @param MovieService $movieService
     * @param LogService $logService
     */
    public function __construct(
        PayseraPaymentService $payseraPaymentService,
        MovieService $movieService,
        LogService $logService
    ) {
        $this->payseraPaymentService = $payseraPaymentService;
        $this->movieService = $movieService;
        $this->logService = $logService;
    }

    /**
     * @Route("/new/{movie}")
     * @param Movie $movie
     *
     * @return Response
     * @throws Exception
     */
    public function buyMovie(Movie $movie): Response
    {
        if (!$this->movieService->isValidForPurchase($movie, $this->getUser())) {
            return $this->json('Filmas nera galimas pirkimui. Susisiekite su administracija', 400);
        }

        try {
            $response = $this->payseraPaymentService->handleMoviePayment($movie, $this->getUser());

            return $this->json($response);
        } catch (Exception $e ) {
            return new Response($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/buy/subscription")
     *
     * @return Response
     * @throws Exception
     */
    public function buySubscription(): Response
    {
        try {
            $response = $this->payseraPaymentService->handleSubscriptionPayment($this->getUser());

            return $this->json($response);
        } catch (Exception $e ) {
            return new Response($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
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
        $log = $this->logService->createByType(Log::TYPE_PAYSERA_SUCCESS);
        $payseraPayment->setToken($request->get('data'));
        $role = $payseraPayment->getUser()->getRole();
        $this->payseraPaymentService->handleSuccessfulPayment($payseraPayment);
        $this->logService->updateByData($log, 'Paysera payment ID' . $payseraPayment->getId(), Log::STATUS_OK);

        if ($payseraPayment->getType() === PayseraPayment::TYPE_SUBSCRIPTION &&
            $role !== $payseraPayment->getUser()->getRole()) {
            $this->addFlash('success', 'Klubo narystė aktyvuota sėkmingai! Prisijunkite iš naujo.');

            return $this->redirectToRoute('app_login', ['email' => $payseraPayment->getUser()->getEmail()]);
        }

        $this->addFlash('success', 'Mokėjimas atliktas sėkmingai');

        return $this->redirectToRoute('home_index');
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
        $log = $this->logService->createByData('Paysera Payment ID = ' . $payseraPayment->getId(),
            null,
            json_encode($request->query->all()),
            null,
            Log::TYPE_PAYSERA_CALLBACK
        );

        try {
            $response = $this->payseraPaymentService->handleCallbackPayment($payseraPayment, $request->query->all());
            $this->logService->setOk($log, null, json_encode($response));
        } catch (Exception $e) {
            $this->logService->setNok($log, null, $e->getMessage());

            return new Response($e->getMessage());
        }

        return new Response('OK');
    }
}

