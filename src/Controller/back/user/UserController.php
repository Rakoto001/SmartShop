<?php

namespace App\Controller\back\user;

use App\Entity\User;
use App\Entity\Roles;
use App\Form\UserType;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/user")
 */
class UserController extends AbstractController
{
    private $userService;

    public function __construct(UserService $_userService)
    {
        $this->userService = $_userService;
    }
    /**
     * @Route("/list", name="admin_user_list")
     */
    public function list()
    {
        $page     = 'Utilisateur';
        $action   = 'Création'; 
        return $this->render('back/user/user-list.html.twig', [
                                                                'page' => $page,
                                                                'action' => $action,
        ]);
    }

    /**
     * @Route("/create", name="admin_user_create")
     */
    public function userCreate(Request $request, EntityManagerInterface $manager)
    {
        $user     = new User;
        $page     = 'Utilisateur';
        $action   = 'Création';
        //par défaut
        $role = new Roles;

        $user->setAvatar('avatarExample.jpg');
        $user->setPassword('123456');
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $allDatas = $request->request->get('user');
            $allFiles = $request->files->get('userAvatar');
            $allDatas['userAvatar'] = $allFiles;
            
            try {
                 $this->userService->checkUser($allDatas);

            } catch (\Throwable $error) {
                dd($error);
            }
            $this->addFlash('success', 'Utilisateur ajouté');

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('back/user/user-action.html.twig', [
                                                                    'userForm' => $userForm->createView(),
                                                                    'page'     => $page,
                                                                    'action'   => $action,
        ]);

    }
}