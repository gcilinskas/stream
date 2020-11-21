<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PayseraPaymentController
 *
 * @Route("/paysera")
 */
class PayseraPaymentController
{
    /**
     * @Route("/call_paysera")
     * @param Request $request
     */
    public function callPaysera(Request $request)
    {

    }

    /**
     * @Route("/register_existing")
     * @param Request $request
     */
    public function callExistingPaysera(Request $request)
    {

    }

    /**
     * @Route("/cancel_payment")
     * @param Request $request
     */
    public function cancelPayment(Request $request)
    {

    }

    /**
     * @Route("/callback_paysera")
     * @param Request $request
     */
    public function callbackPaysera(Request $request)
    {

    }
}
