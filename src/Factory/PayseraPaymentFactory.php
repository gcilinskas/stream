<?php

namespace App\Factory;

use App\Entity\Movie;
use App\Entity\PayseraPayment;
use App\Entity\User;
use App\Service\PayseraPaymentService;
use Exception;

/**
 * Class PayseraPaymentFactory
 */
class PayseraPaymentFactory
{
    /**
     * @var PayseraPaymentService
     */
    private $payseraPaymentService;

    /**
     * @var PriceFactory
     */
    private $priceFactory;

    /**
     * PayseraPaymentFactory constructor.
     *
     * @param PriceFactory $priceFactory
     */
    public function __construct(PriceFactory $priceFactory)
    {
        $this->priceFactory = $priceFactory;
    }

    /**
     * @required
     * @param PayseraPaymentService $payseraPaymentService
     */
    public function setPayseraPaymentService(PayseraPaymentService $payseraPaymentService)
    {
        $this->payseraPaymentService = $payseraPaymentService;
    }

    /**
     * @param Movie $movie
     * @param User $user
     *
     * @return PayseraPayment
     * @throws Exception
     */
    public function createNotPaidMoviePayment(Movie $movie, User $user): PayseraPayment
    {
        $payseraPayment = (new PayseraPayment())->setMovie($movie)
            ->setPrice($movie->getActivePriceByUser($user))
            ->setUser($user)
            ->setStatus(PayseraPayment::STATUS_NOT_PAID)
            ->setType(PayseraPayment::TYPE_TICKET);

        return $this->payseraPaymentService->create($payseraPayment);
    }

    /**
     * @param User $user
     *
     * @return PayseraPayment
     * @throws Exception
     */
    public function createNotPaidSubscriptionPayment(User $user): PayseraPayment
    {
        $payseraPayment = (new PayseraPayment())
            ->setPrice($this->priceFactory->createSubscriptionPrice())
            ->setUser($user)
            ->setStatus(PayseraPayment::STATUS_NOT_PAID)
            ->setType(PayseraPayment::TYPE_SUBSCRIPTION);

        return $this->payseraPaymentService->create($payseraPayment);
    }
}
