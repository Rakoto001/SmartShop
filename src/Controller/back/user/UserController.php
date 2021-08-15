<?php

namespace App\Controller\back\user;

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
}