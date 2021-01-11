<?php

namespace App\Controller;

use App\Entity\Log;
use App\Service\EmailSender;
use App\Service\LogService;
use App\Service\UserService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * @var LogService
     */
    private $logService;

    /**
     * SecurityController constructor.
     *
     * @param UserService $userService
     * @param EmailSender $emailSender
     * @param UserPasswordEncoderInterface $encoder
     * @param LogService $logService
     */
    public function __construct(
        UserService $userService,
        EmailSender $emailSender,
        UserPasswordEncoderInterface $encoder,
        LogService $logService
    ) {
        $this->userService = $userService;
        $this->emailSender = $emailSender;
        $this->encoder = $encoder;
        $this->logService = $logService;
    }


    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @param Request $request
     *
     * @return Response
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
    public function resetPassword(Request $request): Response
    {
        if ($request->getMethod() === "POST") {
            try {
                $user = $this->userService->getOneBy(['email' => $request->get('email')]);
                if (!$user) {
                    throw new NotFoundHttpException('Vartotojo su tokiu el-paštu nėra');
                }

                $user = $this->userService->resetRandomPassword($user, false);
                $this->emailSender->sendResetPasswordMail($user);
                $this->userService->flush();
                $this->addFlash('success', 'Slaptažodis atstatytas. Informacija išsiųsta į jūsų el-paštą');

                return $this->redirectToRoute('app_login');
            } catch (NotFoundHttpException $e) {
                $error = $e->getMessage();
            } catch (Exception $e) {
                $this->logService->createNok(Log::TYPE_EMAIL_RESET_PASSWORD, $e->getMessage());
                $error = 'Nepavyko išsiųsti laiško. ' . $e->getMessage();
            }
        }

        return $this->render('app/reset-password/index.html.twig', ['error' => isset($error) ? $error : null]);
    }
}
