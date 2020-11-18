<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LoginController
 *
 * @Route("/login")
 */
class LoginController extends AbstractController
{
    /**
     * @Route("/", name="login_index")
     */
    public function login()
    {
        return $this->render('app/login/index.html.twig', []);
    }
}
