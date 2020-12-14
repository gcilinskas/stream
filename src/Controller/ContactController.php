<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DukController
 * @Route("/contact")
 */
class ContactController extends AbstractController
{
    /**
     * @Route("/", name="app_contact")
     */
    public function index(): Response
    {
        return $this->render('app/contact/index.html.twig');
    }
}
