<?php

namespace App\Controller;

use App\Service\EmailSender;
use App\Service\UserService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 */
class SecurityController extends AbstractController
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var EmailSender
     */
    private $emailSender;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * SecurityController constructor.
     *
     * @param UserService $userService
     * @param EmailSender $emailSender
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserService $userService, EmailSender $emailSender, UserPasswordEncoderInterface $encoder)
    {
        $this->userService = $userService;
        $this->emailSender = $emailSender;
        $this->encoder = $encoder;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $request->get('email') ? $request->get('email') : $authenticationUtils->getLastUsername();

        return $this->render('app/login/index.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/reset-password", name="app_reset_password")
     * @param Request $request
     *
     * @return Response
     * @throws Exception
     */
    public function resetPassword(Request $request)
    {
        if ($request->getMethod() === "POST") {
            $email = $request->get('email');
            $user = $this->userService->getOneBy(['email' => $email]);

            if (!$user) {
                return $this->render('app/reset-password/index.html.twig', ['error' => 'Vartotojo su tokiu el-paštu nėra']);
            }

            try {
                $plainPassword = random_int(10000, 100000);
                $encodedPassword = $this->encoder->encodePassword($user, $plainPassword);
                $user->setPassword($encodedPassword);
                $this->emailSender->send(
                    $email,
                    $email,
                    'Slaptazodzio Atstatymas',
                    'Jūsų slaptažodis buvo atstatytas. Dabartinis slaptažodis: ' . $plainPassword
                );
                $this->userService->update($user);
            } catch (Exception $e) {
                var_dump($e);
                die();
                return $this->render('app/reset-password/index.html.twig', ['error' => 'Nepavyko išsiųsti laiško. Susisiekite su administracija']);
            }

            $this->addFlash('success', 'Slaptažodis atstatytas. Informacija išsiųsta į jūsų el-paštą');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('app/reset-password/index.html.twig', ['error' => null]);
    }
}
