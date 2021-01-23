<?php

namespace App\Repository;

use App\Entity\Movie;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    /**
     * @return Movie[]|null
     */
    public function findAllOrdered(): ?array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.date', 'ASC')
            ->where('m.deletedAt is null')
            ->andWhere('m.date >= :today')
            ->orWhere('m.dateTo >= :today')
            ->setParameter('today', (new DateTime())->setTime(0,0, 0))
            ->getQuery()->getResult();
    }

    /**
     * @return Movie[]|null
     */
    public function findAllNotDeleted(): ?array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.date', 'DESC')
            ->where('m.deletedAt is null')
            ->getQuery()->getResult();
    }

    /**
     * @param int $limit
     *
     * @return Movie[]|null
     */
    public function findNewestMovies(int $limit = 5): ?array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.date', 'ASC')
            ->where('m.deletedAt is null')
            ->andWhere('m.date >= :today')
            ->orWhere('m.dateTo >= :today')
            ->setParameter('today', (new DateTime())->setTime(0,0, 0))
            ->setMaxResults($limit)
            ->getQuery()->getResult();
    }
}
