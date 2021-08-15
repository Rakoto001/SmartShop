<?php

namespace App\Controller\back\user;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/list", name="admin_user_list")
     */
    public function list()
    {
        return $this->render('back/user/user-list.html.twig');
    }

    /**
     * @Route("/create", name="admin_user_create")
     */
    public function userCreate(Request $request)
    {
        $user = new User;
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
                dd($userForm);
        }

        return $this->render('back/user/user-action.html.twig', [
                                                                    'userForm' => $userForm->createView(),
        ]);

    }
}