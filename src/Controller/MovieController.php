<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Service\CategoryService;
use App\Service\MovieService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

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
     * @var SerializerInterface
     */
    private $serializerInterface;

    /**
     * MovieController constructor.
     *
     * @param MovieService $movieService
     * @param CategoryService $categoryService
     * @param SerializerInterface $serializerInterface
     */
    public function __construct(
        MovieService $movieService,
        CategoryService $categoryService,
        SerializerInterface $serializerInterface
    ) {
        $this->movieService = $movieService;
        $this->categoryService = $categoryService;
        $this->serializerInterface = $serializerInterface;
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

    /**
     * @Route("/{movie}", name="app_movie_get")
     * @param Movie $movie
     *
     * @return Response
     */
    public function getMovie(Movie $movie): Response
    {
//        $response = $this->serializerInterface->serialize(['data' => $movie], 'json', ['groups' => 'ajax_movie']);

        return $this->json(['description' => $movie->getDescription(), 'title' => $movie->getTitle()]);
    }
}
