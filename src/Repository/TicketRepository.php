<?php

namespace App\Repository;

use App\Entity\Movie;
use App\Entity\PayseraPayment;
use App\Entity\Ticket;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ticket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ticket|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ticket[]    findAll()
 * @method Ticket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    /**
     * @param Movie $movie
     * @param User $user
     *
     * @return Ticket[]|null
     */
    public function findAllPaidUnusedByMovieAndUser(Movie $movie, User $user): ?array
    {
        return $this->createQueryBuilder('t')
            ->leftJoin(PayseraPayment::class, 'tp', Join::WITH, 'tp = t.payseraPayment')
            ->where('t.status = :unusedStatus')
            ->andWhere('tp.status = :paidStatus')
            ->andWhere('t.movie = :movie')
            ->andWhere('t.user = :user')
            ->setParameters([
                'unusedStatus' => Ticket::STATUS_UNUSED,
                'paidStatus' => PayseraPayment::STATUS_PAID,
                'user' => $user,
                'movie' => $movie
            ])->getQuery()->getResult();
    }

    /**
     * @param User $user
     *
     * @return Ticket[]|null
     */
    public function findAllFutureTicketsByUser(User $user): ?array
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.movie', 'tm')
            ->where('t.user = :user')
            ->andWhere('(tm.date >= :today OR tm.dateTo >= :today)')
            ->setParameters([
                'today' => (new DateTime())->setTime(0,0, 0),
                'user' => $user
            ])
            ->getQuery()->getResult();
    }
}
