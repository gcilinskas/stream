<?php

namespace App\Service;

use App\Entity\Ticket;
use Exception;

/**
 * Class TicketService
 */
class TicketService extends BaseService
{
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
}
