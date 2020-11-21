<?php

namespace App\Controller;

use App\Service\MovieService;
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
     * TicketController constructor.
     *
     * @param MovieService $movieService
     */
    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    /**
     * @Route("/", name="ticket_index")
     */
    public function index()
    {
        return $this->render('app/ticket/index.html.twig', ['movies' => $this->movieService->getAllOrdered()]);
    }
}
