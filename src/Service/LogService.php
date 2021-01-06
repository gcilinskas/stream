<?php

namespace App\Service;

use App\Entity\Log;
use Exception;

/**
 * Class LogService
 */
class LogService extends BaseService
{
    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return Log::class;
    }

    /**
     * @param Log $log
     *
     * @throws Exception
     */
    public function delete(Log $log)
    {
        parent::remove($log);
    }
}
