<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\App\User\CreateType;
use App\Service\UserService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RegisterController
 *
 * @Route("/register")
 */
class RegisterController extends AbstractController
{
    /**
     * @Route("/", name="register_index")
     *
     * @param Request $request
     * @param FormFactoryInterface $formFactory
     * @param UserService $userService
     *
     * @return Response
     * @throws Exception
     */
    public function index(
        Request $request,
        FormFactoryInterface $formFactory,
        UserService $userService
    ) {
        $user = new User();
        $form = $formFactory->create(CreateType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userService->create($user);

            return $this->redirectToRoute('app_login', ['email' => $user->getEmail()]);
        }

        return $this->render('app/register/index.html.twig', ['form' => $form->createView()]);
    }

}
