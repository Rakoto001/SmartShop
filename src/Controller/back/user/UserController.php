<?php

namespace App\Controller\back\user;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
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
        $user->setAvatar('nnom.jpg');
        $user->setPassword('123456');
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            dd($user);
                    // try {
            //     $manager->persist($user);
            //     $manager->flush();
            //     // $userService->userRegistration($params);
 
            //  } catch(\Doctrine\DBAL\DBALException $e)
            //  {
            //      //gestion erreur quand le email ou username est déjà utilisé
            //      $this->addFlash('error', 'utilisateur dejà existant');
            //      return $this->redirectToRoute('admin_user_create');
            //  }

             try {
                $manager->persist($user);
                $manager->flush();
            } catch (\Throwable $error) {
                dd($error);
            }
        }

        return $this->render('back/user/user-action.html.twig', [
                                                                    'userForm' => $userForm->createView(),
                                                                    'page'     => $page,
                                                                    'action'   => $action,
        ]);

    }
}