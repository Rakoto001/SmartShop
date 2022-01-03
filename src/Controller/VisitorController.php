<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class VisitorController extends AbstractController
{

    /** UserService */
    private $userService;

    public function __construct(UserService $_userService) {
        $this->userService = $_userService;
    }
  
  /**
   * @Route("/login", name="front_login")
   *
   * @return Response
   */
    public function login(): Response
    {
    
     
        // $error = $authenticationUtils->getLastAuthenticationError();
        // // last username entered by the user
        // $lastUsername = $authenticationUtils->getLastUsername();

        // $this->findOneBy(['email'] => )
        return $this->render('front/login.html.twig', [
            
        ]);
    }

    /**
     * @Route("/login/check/confirmation", name="is_confirmed_account")
     *
     * @param Request $request
     * @return boolean
     */
    public function isConfirmedAccount(Request $request)
    {
        if ($user = $this->userService->findOneBy(['email' => $request->request->get('_username')])) {
            if ($user[0]->getConfirmationAccount() != 1) {
                $user[0]->setConfirmationAccount(1);
                $this->userService->save($user[0]);
                $this->addFlash('success', 'Votre compte a été activé avec success');


            }
            // return $this->redirectToRoute('front_main');

            return new Response('ok');

        }

        return $this->redirectToRoute('front_login');


    }

    /**
     * @Route("/user/register", name="user_register")
     * @return void
     */
    public function userRegistration(Request $request)
    {
        $user = new User;
        $registrationForm = $this->createForm(RegistrationType::class, $user);
        $registrationForm->handleRequest($request);
        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            $aParamsUserRegister = ($request->request->all()['registration']);
            unset($aParamsUserRegister['_token']);
            try {
                $this->userService->registerNewUser($aParamsUserRegister);

            } catch (\Throwable $error) {
            $this->addFlash('error', 'cette compte existe déjà');
            return $this->redirectToRoute('user_register');


            }
           

            $this->addFlash('notice', 'compte crée, consulter votre mail pour la confirmation du compte');

            return $this->redirectToRoute('front_login');
        
        }
        

        return $this->render('front/register.html.twig', [
                                                            'registerForm' => $registrationForm->createView(),
        ]);

    }

    
    public function logout(): Response
    {
        return $this->render('visitor/index.html.twig', [
            'controller_name' => 'VisitorController',
        ]);
    }
}
