<?php

namespace App\Controller\Admin;

use App\Entity\Movie;
use App\Factory\PriceFactory;
use App\Form\Admin\Movie\CreateType;
use App\Service\CategoryService;
use App\Service\FileService;
use App\Service\MovieService;
use App\Service\PriceService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MovieController
 * @Route("/movie")
 */
class MovieController extends AbstractController
{
    /**
     * @var MovieService
     */
    private $movieService;

    /**
     * @var FileService
     */
    private $fileService;

    /**
     * @var CategoryService
     */
    private $categoryService;

    /**
     * @var PriceFactory
     */
    private $priceFactory;

    /**
     * @var PriceService
     */
    private $priceService;

    /**
     * MovieController constructor.
     *
     * @param MovieService $movieService
     * @param FileService $fileService
     * @param CategoryService $categoryService
     * @param PriceFactory $priceFactory
     * @param PriceService $priceService
     */
    public function __construct(
        MovieService $movieService,
        FileService $fileService,
        CategoryService $categoryService,
        PriceFactory $priceFactory,
        PriceService $priceService
    ) {
        $this->movieService = $movieService;
        $this->fileService = $fileService;
        $this->categoryService = $categoryService;
        $this->priceFactory = $priceFactory;
        $this->priceService = $priceService;
    }

    /**
     * @Route("/add", name="admin_movie_add")
     * @param Request $request
     *
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function add(Request $request)
    {
        $movie = new Movie();
        $form = $this->createForm(CreateType::class, $movie, [
            'action' => $this->generateUrl('admin_movie_add'),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->movieService->create($movie);
            } catch (Exception $e) {
                throw $e;
            }

            return $this->redirectToRoute('admin_movie_list');
        }

        return $this->render('admin/movie/add.html.twig', [
            'form' => $form->createView(),
            'errors' => $form->getErrors(true),
            'categories' => $this->categoryService->getAll()
        ]);
    }

    /**
     * @Route("/edit/{movie}", name="admin_movie_edit")
     * @param Request $request
     *
     * @param Movie $movie
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function edit(Request $request, Movie $movie)
    {
        $form = $this->createForm(CreateType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->movieService->update($movie);
            } catch (Exception $e) {
                throw $e;
            }

            return $this->redirectToRoute('admin_movie_list');
        }

        return $this->render('admin/movie/edit.html.twig', [
            'form' => $form->createView(),
            'errors' => $form->getErrors(true),
            'categories' => $this->categoryService->getAll(),
            'movie' => $movie
        ]);
    }

    /**
     * @Route("/delete/{movie}", name="admin_movie_delete")
     * @param Movie $movie
     *
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function delete(Movie $movie)
    {
        $this->movieService->softDelete($movie);

        return $this->redirectToRoute('admin_movie_list');
    }

    /**
     * @Route("/list", name="admin_movie_list")
     */
    public function list()
    {
        return $this->render('admin/movie/list.html.twig', [
            'movies' => $this->movieService->getAllNotDeleted()
        ]);
    }
}
