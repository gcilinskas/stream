<?php

namespace App\Controller;

use App\Service\MovieService;
use App\Service\TicketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
    public function index()
    {
        return $this->render('app/ticket/index.html.twig',
            [
                'userTickets' => $this->ticketService->getBy(['user' => $this->getUser()]),
                'movies' => $this->movieService->getAllOrdered()
            ]
        );
    }
}
