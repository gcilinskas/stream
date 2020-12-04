<?php

namespace App\Controller;

use App\Service\CategoryService;
use App\Service\MovieService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MovieController
 *
 * @Route("/movie")
 */
class MovieController extends AbstractController
{
    /**
     * @var MovieService
     */
    private $movieService;

    /**
     * @var CategoryService
     */
    private $categoryService;

    /**
     * MovieController constructor.
     *
     * @param MovieService $movieService
     * @param CategoryService $categoryService
     */
    public function __construct(MovieService $movieService, CategoryService $categoryService)
    {
        $this->movieService = $movieService;
        $this->categoryService = $categoryService;
    }

    /**
     * @Route("/", name="app_movie_index")
     */
    public function index(): Response
    {
        return $this->render('app/movie/index.html.twig',
            [
                'newestMovies' => $this->movieService->getNewestMovies(),
                'movies' => $this->movieService->getAllOrdered(),
                'categories' => $this->categoryService->getAll()
            ]
        );
    }
}
