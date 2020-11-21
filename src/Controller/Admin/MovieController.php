<?php

namespace App\Controller\Admin;

use App\Entity\Movie;
use App\Factory\PriceFactory;
use App\Form\Admin\Movie\CreateType;
use App\Service\CategoryService;
use App\Service\FileService;
use App\Service\MovieService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
     * MovieController constructor.
     *
     * @param MovieService $movieService
     * @param FileService $fileService
     * @param CategoryService $categoryService
     * @param PriceFactory $priceFactory
     */
    public function __construct(
        MovieService $movieService,
        FileService $fileService,
        CategoryService $categoryService,
        PriceFactory $priceFactory
    ) {
        $this->movieService = $movieService;
        $this->fileService = $fileService;
        $this->categoryService = $categoryService;
        $this->priceFactory = $priceFactory;
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
        $form = $this->createForm(CreateType::class, $movie);

        if ($request->getMethod() === "POST") {
            $form->submit($request->request->all() + $request->files->all(), true);
            if ($form->isSubmitted() && $form->isValid()) {

                if ($request->get('price')) {
                    $this->priceFactory->create($movie, $request->get('price'));
                }

                /** @var UploadedFile $movieFile */
                $movieFile = $form->get('movieFile')->getData();
                $movieImageFile = $form->get('imageFile')->getData();

                if ($movieFile) {
                    $movieFilename = $this->fileService->upload($movieFile);
                    $movie->setMovie($movieFilename);
                }

                if ($movieImageFile) {
                    $movieImageFilename = $this->fileService->uploadImage($movieImageFile);
                    $movie->setImage($movieImageFilename);
                }

                try {
                    $this->movieService->create($movie);
                } catch (Exception $e) {
                    throw $e;
                }

                return $this->redirectToRoute('admin_movie_list');
            }
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

        if ($request->getMethod() === "POST") {
            $form->submit($request->request->all() + $request->files->all(), true);
            if ($form->isSubmitted() && $form->isValid()) {

                if ($request->get('price') && (int)$request->get('price') !== (int)$movie->getFormattedActivePrice()) {
                    $this->priceFactory->create($movie, $request->get('price'));
                }

                /** @var UploadedFile $movieFile */
                $movieFile = $form->get('movieFile')->getData();
                $movieImageFile = $form->get('imageFile')->getData();

                if ($movieFile) {
                    $this->fileService->removeMovieFile($movie);
                    $movieFilename = $this->fileService->upload($movieFile);
                    $movie->setMovie($movieFilename);
                }

                if ($movieImageFile) {
                    $this->fileService->removeMovieImage($movie);
                    $movieImageFilename = $this->fileService->uploadImage($movieImageFile);
                    $movie->setImage($movieImageFilename);
                }

                try {
                    $this->movieService->update($movie);
                } catch (Exception $e) {
                    throw $e;
                }

                return $this->redirectToRoute('admin_movie_list');
            }
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
        $this->movieService->delete($movie);

        return $this->redirectToRoute('admin_movie_list');
    }

    /**
     * @Route("/list", name="admin_movie_list")
     */
    public function list()
    {
        return $this->render('admin/movie/list.html.twig', [
            'movies' => $this->movieService->getAllOrdered()
        ]);
    }
}
