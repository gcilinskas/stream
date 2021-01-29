<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Ticket;
use App\Service\MovieService;
use App\Service\TicketService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class TicketController
 *
 * @Route("/ticket")
 */
class TicketController extends AbstractController
{
    /**
     * @var MovieService
     */
    private $movieService;

    /**
     * @var TicketService
     */
    private $ticketService;

    /**
     * TicketController constructor.
     *
     * @param MovieService $movieService
     * @param TicketService $ticketService
     */
    public function __construct(MovieService $movieService, TicketService $ticketService)
    {
        $this->movieService = $movieService;
        $this->ticketService = $ticketService;
    }

    /**
     * @Route("/", name="app_ticket_index")
     */
    public function index(): Response
    {
        return $this->render('app/ticket/index.html.twig',
            [
                'userTickets' => $this->ticketService->getAllFutureTicketsByUser($this->getUser())
            ]
        );
    }

    /**
     * @Route("/use/{ticket}", name="app_ticket_use")
     * @param Ticket $ticket
     *
     * @return Response
     * @throws Exception
     */
    public function ajaxUseTicket(Ticket $ticket)
    {
        //TODO:: voter
        $ticket->setStatus(Ticket::STATUS_USED);
        $this->ticketService->update($ticket);

        return $this->json($ticket->getMovie()->getId());
    }
}
