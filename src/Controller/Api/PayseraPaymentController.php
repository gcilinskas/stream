<?php

namespace App\Controller\Api;

use App\Entity\PayseraPayment;
use App\Form\Api\PayseraPayment\CreateType;
use App\Service\PayseraPaymentService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
     * PayseraPaymentController constructor.
     *
     * @param PayseraPaymentService $payseraPaymentService
     */
    public function __construct(PayseraPaymentService $payseraPaymentService)
    {
        $this->payseraPaymentService = $payseraPaymentService;
    }

    /**
     * @Route("/new")
     * @param Request $request
     *
     * @return Response
     * @throws Exception
     */
    public function new(Request $request)
    {
        $payseraPayment = new PayseraPayment();
        $form = $this->createForm(CreateType::class, $payseraPayment);
        $form->submit($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$payseraPayment->getMovie()->isValidForPurchase()) {
                throw new Exception('Filmas neturi kainos arba transliavimo data yra nebegaliojanti');
            }

            $payseraPayment = $this->payseraPaymentService->create($payseraPayment);

            try {
                $response = $this->payseraPaymentService->pay($payseraPayment);
                return new Response(json_encode($response, JSON_UNESCAPED_SLASHES), Response::HTTP_OK);
            } catch ( WebToPayException $e ) {
                return new Response('Neteisingai atlikta uzklausa i Paysera', Response::HTTP_BAD_REQUEST);
            }
        }

        return $this->json(['errors' => $form->getErrors(true)], 400);
    }

    /**
     * @Route("/cancel/{payseraPayment}")
     * @param PayseraPayment $payseraPayment
     *
     * @return Response
     * @throws Exception
     */
    public function cancelPayment(PayseraPayment $payseraPayment)
    {
        $payseraPayment->setStatus(PayseraPayment::STATUS_CANCELED);
        $this->payseraPaymentService->update($payseraPayment);

        return $this->json(['response' => PayseraPayment::STATUS_CANCELED], 200);
    }

    /**
     * @Route("/callback/{payseraPayment}")
     * @param PayseraPayment $payseraPayment
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function callbackPaysera(PayseraPayment $payseraPayment)
    {
        $payseraPayment->setStatus(PayseraPayment::STATUS_PAID);
        $this->payseraPaymentService->update($payseraPayment);

        //TODO::redirect to ticket code generator
        return $this->json(['response' => PayseraPayment::STATUS_PAID], 200);
    }

    /**
     * @Route("/success/{payseraPayment}")
     * @param PayseraPayment $payseraPayment
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function success(PayseraPayment $payseraPayment)
    {
        $payseraPayment->setStatus(PayseraPayment::STATUS_SUCCESS);
        $this->payseraPaymentService->update($payseraPayment);

        //TODO:: not sure what is happening
        return $this->json(['response' => PayseraPayment::STATUS_SUCCESS], 200);
    }
}
