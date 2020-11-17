<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LogoutController
 */
class LogoutController extends AbstractController
{
    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        return $this->render('app/login/index.html.twig', []);
    }
}
