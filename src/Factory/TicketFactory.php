<?php

namespace App\Factory;

use App\Entity\PayseraPayment;
use App\Entity\Ticket;
use App\Service\TicketService;
use Exception;

/**
 * Class TicketFactory
 */
class TicketFactory
{
    /**
     * @var TicketService
     */
    private $ticketService;

    /**
     * TicketFactory constructor.
     *
     * @param TicketService $ticketService
     */
    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    /**
     * @param PayseraPayment $payseraPayment
     *
     * @return mixed
     * @throws Exception
     */
    public function createForPayment(PayseraPayment $payseraPayment)
    {
        $ticket = new Ticket();

        $ticket->setPayseraPayment($payseraPayment)
            ->setStatus(Ticket::STATUS_UNUSED)
            ->setMovie($payseraPayment->getMovie())
            ->setUser($payseraPayment->getUser())
            ->setCode(strtoupper(uniqid()));

        return $this->ticketService->create($ticket);
    }
}
