<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\App\Movie\CreateType;
use App\Service\FileUploader;
use App\Service\MovieService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
     * @var FileUploader
     */
    private $fileUploader;

    /**
     * MovieController constructor.
     *
     * @param MovieService $movieService
     * @param FileUploader $fileUploader
     */
    public function __construct(MovieService $movieService, FileUploader $fileUploader)
    {
        $this->movieService = $movieService;
        $this->fileUploader = $fileUploader;
    }

    /**
     * @Route("/new", name="app_movie_new")
     * @param Request $request
     *
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function new(Request $request)
    {
        $movie = new Movie();
        $form = $this->createForm(CreateType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $movieFile */
            $movieFile = $form->get('movieFile')->getData();

            if ($movieFile) {
                $movieFilename = $this->fileUploader->upload($movieFile);
                $movie->setFilename($movieFilename);
            }
            $this->movieService->create($movie);

            return $this->redirectToRoute('app_product_list');
        }

        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
