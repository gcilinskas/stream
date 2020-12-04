<?php

namespace App\Service;

use App\Entity\Movie;
use App\Entity\Price;
use Exception;

class PriceService extends BaseService
{
    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return Price::class;
    }

    /**
     * @param Movie $movie
     * @param bool $isClubPrice
     *
     * @throws Exception
     */
    public function deactivateMoviePrices(Movie $movie, bool $isClubPrice = false)
    {
        /** @var Price[] $prices */
        $prices = $this->getBy(['movie' => $movie, 'clubPrice' => $isClubPrice]) ?? [];

        foreach ($prices as $price) {
            $price->setActive(false);
            $this->update($price, false);
        }

        $this->flush();
    }
}
