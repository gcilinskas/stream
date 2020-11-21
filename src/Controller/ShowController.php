<?php

namespace App\Controller;

use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ShowController
 * @Route("/show")
 */
class ShowController extends AbstractController
{
    /**
     * @Route("/{movie}", name="app_show_index")
     * @param Movie $movie
     *
     * @return Response
     */
    public function index(Movie $movie)
    {
        return $this->render('app/show/index.html.twig', [
            'movie' => $movie
        ]);
    }
}
