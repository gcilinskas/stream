<?php

namespace App\Service;

use App\Entity\PayseraPayment;
use WebToPay;
use WebToPayException;

/**
 * Class PayseraPaymentService
 */
class PayseraPaymentService extends BaseService
{
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
    public function pay(PayseraPayment $payseraPayment)
    {
        $request = WebToPay::buildRequest(
            [
                'projectid' => $_ENV['PAYSERA_PROJECT_ID'],
                'sign_password' => $_ENV['PAYSERA_SECRET_KEY'],
                'orderid' => $payseraPayment->getId(),
                'amount' => $payseraPayment->getPrice()->getAmount(),
                'currency' => $payseraPayment->getPrice()->getCurrency(),
                'country' => $payseraPayment->getPaymentAddress()->getCountry(),
                'accepturl'=> $_ENV['DOMAIN'] . '/api/paysera/success/' . $payseraPayment->getId(),
                'cancelurl' => $_ENV['DOMAIN'] . '/api/paysera/cancel/' . $payseraPayment->getId(),
                'callbackurl' => $_ENV['DOMAIN'] . '/api/paysera/callback/' . $payseraPayment->getId(),
                'test' => $_ENV['PAYSERA_TEST'],
                'p_firstname' => $payseraPayment->getPaymentAddress()->getFirstName(),
                'p_lastname' => $payseraPayment->getPaymentAddress()->getLastName(),
            ]
        );

        return ["url" => WebToPay::PAYSERA_PAY_URL . '?data=' . $request['data'] . '&amp;sign=' . $request['sign']];
    }
}
