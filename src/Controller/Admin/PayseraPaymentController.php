<?php

namespace App\Controller\Admin;

use App\Entity\PayseraPayment;
use App\Service\PayseraPaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/list", name="admin_paysera_list")
     */
    public function list()
    {
        return $this->render('admin/paysera/list.html.twig', [
            'payseraPayments' => $this->payseraPaymetService->getBy(['status' => PayseraPayment::STATUS_PAID]),
        ]);
    }
}
