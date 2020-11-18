<?php

namespace App\Service;

use App\Entity\Movie;

/**
 * Class MovieService
 */
class MovieService extends BaseService
{
    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return Movie::class;
    }
}
