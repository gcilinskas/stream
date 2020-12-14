<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DukController
 * @Route("/duk")
 */
class DukController extends AbstractController
{
    /**
     * @Route("/", name="app_duk_index")
     */
    public function index(): Response
    {
        return $this->render('app/duk/index.html.twig');
    }
}
