<?php

namespace App\Service;

use App\Entity\Movie;
use App\Entity\Ticket;
use App\Entity\User;
use App\Repository\TicketRepository;
use Exception;

/**
 * Class TicketService
 */
class TicketService extends BaseService
{
    /**
     * @var TicketRepository
     */
    protected $repository;

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return Ticket::class;
    }

    /**
     * @param Ticket $ticket
     *
     * @throws Exception
     */
    public function delete(Ticket $ticket)
    {
        parent::remove($ticket);
    }

    /**
     * @param Movie $movie
     * @param User $user
     *
     * @return Ticket[]|null
     */
    public function getAllPaidUnusedByMovieAndUser(Movie $movie, User $user): ?array
    {
        return $this->repository->findAllPaidUnusedByMovieAndUser($movie, $user);
    }

    /**
     * @param User $user
     *
     * @return Ticket[]|null
     */
    public function getAllFutureTicketsByUser(User $user): ?array
    {
        return $this->repository->findAllFutureTicketsByUser($user);
    }

}
