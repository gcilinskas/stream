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

    /**
     * @param string $type
     * @param string|null $info
     *
     * @return Log
     * @throws Exception
     */
    public function createNok(string $type, ?string $info = null): Log
    {
        $log = (new Log())->setStatus(LOG::STATUS_NOK)
            ->setType($type)
            ->setInfo($info);

        return $this->create($log);
    }
}
