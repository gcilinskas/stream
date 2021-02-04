<?php

namespace App\Factory;

use App\Entity\Movie;
use App\Entity\Price;
use App\Service\PriceService;
use Exception;

/**
 * Class PriceFactory
 */
class PriceFactory
{
    /**
     * @var PriceService
     */
    private $priceService;

    /**
     * PriceFactory constructor.
     *
     * @param PriceService $priceService
     */
    public function __construct(PriceService $priceService)
    {
        $this->priceService = $priceService;
    }

    /**
     * @param Movie $movie
     * @param int $centAmount
     *
     * @return mixed
     * @throws Exception
     */
    public function createRegularPrice(Movie $movie, int $centAmount)
    {
        $price = new Price();
        $price->setMovie($movie)
            ->setAmount($centAmount)
            ->setActive(true);

        $this->priceService->deactivateMoviePrices($movie, false);

        return $this->priceService->create($price);
    }

    /**
     * @param Movie $movie
     * @param int $centAmount
     *
     * @return mixed
     * @throws Exception
     */
    public function createClubPrice(Movie $movie, int $centAmount)
    {
        $price = new Price();
        $price->setMovie($movie)
            ->setAmount($centAmount)
            ->setActive(true)
            ->setClubPrice(true);

        $this->priceService->deactivateMoviePrices($movie, true);

        return $this->priceService->create($price);
    }

    /**
     * @return Price
     * @throws Exception
     */
    public function createSubscriptionPrice(): Price
    {
        $price = (new Price())->setActive(true)->setAmount(Price::PRICE_SUBSCRIPTION);

        return $this->priceService->create($price);
    }
}
