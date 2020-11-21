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
     * @param Movie $movie
     * @param int $amount
     *
     * @return mixed
     * @throws Exception
     */
    public function create(Movie $movie, int $amount)
    {
        $price = new Price();
        $price->setMovie($movie)
            ->setAmount($amount)
            ->setActive(true);

        $this->priceService->deactivateMoviePrices($movie);

        return $this->priceService->create($price);
    }
}
