<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\MovieService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 */
class HomeController extends AbstractController
{
    /**
     * @var MovieService
     */
    private $movieService;

    /**
     * HomeController constructor.
     *
     * @param MovieService $movieService
     */
    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    /**
     * @Route("/", name="home_index")
     */
    public function index()
    {
        if ($this->getUser() && $this->getUser()->isClubOrAdmin()) {
            return $this->render('app/index.html.twig', ['movie' => $this->movieService->getNewestMovie()]);
        }

        return $this->render('app/about.html.twig');
    }
}
