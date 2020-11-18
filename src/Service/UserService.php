<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class UserService
 */
class UserService extends BaseService
{
    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * UserService constructor.
     *
     * @param EntityManagerInterface   $em
     * @param EventDispatcherInterface $dispatcher
     * @param TokenStorageInterface    $tokenStorage
     */
    public function __construct(
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        TokenStorageInterface $tokenStorage
    ) {
        parent::__construct($em, $dispatcher);
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return User::class;
    }

    /**
     * remove user
     *
     * @param $entity
     * @param bool $flush
     */
    public function remove($entity, bool $flush = true): void
    {
        $this->em->remove($entity);
        if ($flush) {
            $this->em->flush();
        }
    }

    /**
     * @return User
     */
    public function getLoggedInUser(): User
    {
        return $this->tokenStorage->getToken()->getUser();
    }

    /**
     * @return User[]
     */
    public function getAll()
    {
        return $this->repository->findAll();
    }
}
