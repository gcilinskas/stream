<?php

namespace App\Service;

use App\Entity\Subscription;
use App\Repository\SubscriptionRepository;
use DateTime;
use Exception;

/**
 * Class SubscriptionService
 */
class SubscriptionService extends BaseService
{
    /**
     * @var SubscriptionRepository
     */
    protected $repository;

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return Subscription::class;
    }

    /**
     * @param Subscription $entity
     * @param bool $flush
     *
     * @throws Exception
     */
    public function remove($entity, bool $flush = true)
    {
        parent::remove($entity, $flush);
    }

    /**
     * @param Subscription $entity
     * @param bool $flush
     *
     * @return mixed
     * @throws Exception
     */
    public function update($entity, bool $flush = true)
    {
        $entity->setUpdatedAt(new DateTime());

        return parent::update($entity, $flush); // TODO: Change the autogenerated stub
    }
}
