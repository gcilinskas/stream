<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index()
    {
        return $this->render('app/duk/index.html.twig');
    }
}
