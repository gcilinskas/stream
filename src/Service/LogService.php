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

    /**
     * @param string $type
     *
     * @return Log
     * @throws Exception
     */
    public function createByType(string $type): Log
    {
        $log = (new Log())->setType($type);

        return $this->create($log);
    }

    /**
     * @param Log $log
     * @param string|null $info
     * @param string|null $status
     * @param string|null $request
     * @param string|null $response
     * @param string|null $type
     *
     * @return Log
     * @throws Exception
     */
    public function updateByData(
        Log $log,
        ?string $info = null,
        ?string $status = null,
        ?string $request = null,
        ?string $response = null,
        ?string $type = null
    ): Log {
        $log->setInfo($info ? $info : $log->getInfo())
            ->setStatus($status ? $status : $log->getStatus())
            ->setRequest($request ? $request : $log->getRequest())
            ->setResponse($response ? $response : $log->getResponse())
            ->setType($type ? $type : $log->getType());

        return $this->update($log);
    }

    /**
     * @param string|null $info
     * @param string|null $status
     * @param string|null $request
     * @param string|null $response
     * @param string|null $type
     *
     * @return Log
     * @throws Exception
     */
    public function createByData(
        ?string $info = null,
        ?string $status = null,
        ?string $request = null,
        ?string $response = null,
        ?string $type = null
    ): Log {
        return $this->updateByData(new Log(), $info, $status, $request, $response, $type);
    }

    /**
     * @param Log $log
     * @param string|null $info
     * @param string|null $request
     * @param string|null $response
     *
     * @return Log
     * @throws Exception
     */
    public function setNok(
        Log $log,
        ?string $request = null,
        ?string $response = null,
        ?string $info = null
    ): Log {
        $log->setStatus(Log::STATUS_NOK);

        return $this->updateByData($log, $info, Log::STATUS_NOK, $request, $response);
    }

    /**
     * @param Log $log
     * @param string|null $info
     * @param string|null $request
     * @param string|null $response
     *
     * @return Log
     * @throws Exception
     */
    public function setOk(
        Log $log,
        ?string $request = null,
        ?string $response = null,
        ?string $info = null
    ): Log {
        $log->setStatus(Log::STATUS_OK);

        return $this->updateByData($log, $info, Log::STATUS_NOK, $request, $response);
    }
}
