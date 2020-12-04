<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Service\UserService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * UserController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("/list", name="admin_user_list")
     */
    public function list()
    {
        return $this->render('admin/user/list.html.twig', [
            'users' => $this->userService->getAll(),
        ]);
    }

    /**
     * @Route("/list/club/approve", name="admin_user_club_list_approve")
     */
    public function clubApproveList()
    {
        return $this->render('admin/user/club-list-approve.html.twig', [
            'users' => $this->userService->getBy(['clubRequest' => true]),
        ]);
    }

    /**
     * @Route("/club/approve/{user}", name="admin_approve_club_user")
     * @param User $user
     *
     * @return Response
     * @throws Exception
     */
    public function approveClubUserAction(User $user)
    {
        $user->setClubRequest(false)
            ->setRole(User::ROLE_KLUBO_NARYS);
        $this->userService->update($user);

        return $this->render('admin/user/club-list-approve.html.twig', [
            'users' => $this->userService->getBy(['clubRequest' => true]),
        ]);
    }

    /**
     * @Route("/club/disapprove/{user}", name="admin_disapprove_club_user")
     * @param User $user
     *
     * @return Response
     * @throws Exception
     */
    public function disapproveClubUserAction(User $user)
    {
        $user->setClubRequest(false);
        $this->userService->update($user);

        return $this->render('admin/user/club-list-approve.html.twig', [
            'users' => $this->userService->getBy(['clubRequest' => true]),
        ]);
    }

    /**
     * @Route("/admin/{user}", name="admin_user_admin")
     * @param User $user
     *
     * @return Response
     * @throws Exception
     */
    public function makeAdmin(User $user)
    {
        if ($user->isAdmin()) {
            $user->setRole(User::ROLE_USER);
        } else {
            $user->setRole(User::ROLE_ADMIN);
        }

        $this->userService->update($user);

        return $this->render('admin/user/list.html.twig', [
            'users' => $this->userService->getAll(),
        ]);
    }

    /**
     * @Route("/club/{user}", name="admin_user_admin")
     * @param User $user
     *
     * @return Response
     * @throws Exception
     */
    public function approveClubUser(User $user)
    {
        if ($user->isAdmin()) {
            $user->setRole(User::ROLE_USER);
        } else {
            $user->setRole(User::ROLE_ADMIN);
        }
    }
}
