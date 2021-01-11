<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * UserService constructor.
     *
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @param TokenStorageInterface $tokenStorage
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        TokenStorageInterface $tokenStorage,
        UserPasswordEncoderInterface $encoder
    ) {
        parent::__construct($em, $dispatcher);
        $this->tokenStorage = $tokenStorage;
        $this->encoder = $encoder;
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

    /**
     * @param User $user
     * @param bool $flush
     *
     * @return User
     * @throws Exception
     */
    public function resetRandomPassword(User $user, bool $flush = true): User
    {
        $plainPassword = random_int(10000, 100000);
        $encodedPassword = $this->encoder->encodePassword($user, $plainPassword);
        $user->setPassword($encodedPassword)
            ->setPlainPassword($plainPassword);

        return $this->update($user, $flush);
    }
}
