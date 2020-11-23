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
    public function create(Movie $movie, int $centAmount)
    {
        $price = new Price();
        $price->setMovie($movie)
            ->setAmount($centAmount)
            ->setActive(true);

        $this->priceService->deactivateMoviePrices($movie);

        return $this->priceService->create($price);
    }
}
