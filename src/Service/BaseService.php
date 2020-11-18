<?php

namespace App\Service;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class BaseService
 */
abstract class BaseService
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * BaseService constructor.
     *
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;

        $this->setRepository();
    }

    /**
     * set repository
     */
    private function setRepository(): void
    {
        $this->repository = $this->em->getRepository($this->getEntityClass());
    }

    /**
     * @return string
     */
    abstract public function getEntityClass(): string;

    /**
     * get all entities
     *
     * @return array
     */
    public function getAll()
    {
        return $this->repository->findAll();
    }

    /**
     * get QueryBuilder
     *
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        return $this->repository->createQueryBuilder('e');
    }

    /**
     * @param mixed $entity
     * @param bool $flush
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function update($entity, bool $flush = true)
    {
        try {
            $this->persist($entity);

            if ($flush) {
                $this->flush();
            }
        } catch (\Exception $e) {
            throw $e;
        }

        return $entity;
    }

    /**
     * @param mixed $entity
     */
    public function persist($entity)
    {
        $this->em->persist($entity);
    }

    /**
     * flush data to db
     */
    public function flush()
    {
        $this->em->flush();
    }

    /**
     * @param mixed $entity
     * @param bool $flush
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function create($entity, $flush = true)
    {
        try {
            $this->persist($entity);

            if ($flush) {
                $this->flush();
            }
        } catch (\Exception $e) {
            throw $e;
        }

        return $entity;
    }

    /**
     * clean repository
     */
    public function clear(): void
    {
        $this->repository->clear();
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function getOneById(int $id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param null $limit
     * @param null $offset
     *
     * @return mixed
     */
    public function getBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     *
     * @return mixed
     */
    public function getOneBy(array $criteria, array $orderBy = null)
    {
        return $this->repository->findOneBy($criteria, $orderBy);
    }

    public function getByIds(array $ids): array
    {
        return $this->repository->findBy(['id' => $ids]);
    }

    /**
     * @param      $entity
     * @param bool $flush
     *
     * @throws \Exception
     */
    protected function remove($entity, bool $flush = true)
    {
        try {
            $this->em->remove($entity);

            if ($flush) {
                $this->em->flush();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $entity
     */
    public function refresh($entity)
    {
        return $this->em->refresh($entity);
    }
}
