<?php

namespace App\Controller;

use App\Form\App\User\EditType;
use App\Service\UserService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 *
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/edit", name="app_user_edit")
     *
     * @param Request $request
     * @param UserService $userService
     *
     * @return Response
     * @throws Exception
     */
    public function index(Request $request, UserService $userService): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userService->update($user);

            return $this->redirectToRoute('home_index');
        }

        return $this->render('app/user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
