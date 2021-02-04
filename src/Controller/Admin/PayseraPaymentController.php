<?php

namespace App\Controller\Admin;

use App\Entity\PayseraPayment;
use App\Service\PayseraPaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class Transactions
 * @Route("/paysera")
 */
class PayseraPaymentController extends AbstractController
{
    /**
     * @var PayseraPaymentService
     */
    private $payseraPaymetService;

    /**
     * PayseraPaymentController constructor.
     *
     * @param PayseraPaymentService $payseraPaymetService
     */
    public function __construct(PayseraPaymentService $payseraPaymetService)
    {
        $this->payseraPaymetService = $payseraPaymetService;
    }

    /**
     * @Route("/ticket/list", name="admin_paysera_ticket_list")
     */
    public function ticketList(): Response
    {
        return $this->render('admin/paysera/ticket/list.html.twig', [
            'payseraPayments' => $this->payseraPaymetService->getBy(
                [
                    'status' => PayseraPayment::STATUS_PAID,
                    'type' => PayseraPayment::TYPE_TICKET
                ]
            ),
        ]);
    }

    /**
     * @Route("/subscription/list", name="admin_paysera_subscription_list")
     */
    public function subscriptionList(): Response
    {
        return $this->render('admin/paysera/subscription/list.html.twig', [
            'payseraPayments' => $this->payseraPaymetService->getBy(
                [
                    'status' => PayseraPayment::STATUS_PAID,
                    'type' => PayseraPayment::TYPE_SUBSCRIPTION
                ]
            ),
        ]);
    }
}
