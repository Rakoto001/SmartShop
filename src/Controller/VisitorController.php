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
                dd($error->getMessage());
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

      /**
     * @Route("/check/mail", name="check_mail")
     *
     */
    public function checkMailController()
    {
        $validation_path = ($this->getParameter('validation_path'));
        // dd($validation_path);
        $email = 'sf@gmail.com';
        $activation = 1;
        $userRegistredPassword = 'sdsdfsdfsd';
        $templateNewRegisterCustomer = "mail/customermail/confirm-registration-customer.html.twig";
        $parametersMail = [
                            'newCustomerEmail' => $email,
                            'urlBaseSmart' => $validation_path,
                            'userRegistredPassword' => $userRegistredPassword,
                            'activation' => $activation
                            ];


        return $this->render('mail/customermail/confirm-registration-customer.html.twig', [
            'parametersMail' => $parametersMail
            ]
        );
                            
    }

    
      /**
     * @Route("/visitor/activation/{email}", name="activation_register_visitor")
     *
     */
    public function activateRegisterVisitor(string $email, Request $request)
    {
        
        $baseUrl = ($this->getParameter('base_url'));
        $validation_path = ($this->getParameter('validation_path'));
        $localBase = $baseUrl.$validation_path;
        $parametters = $this->userService->activateByMail($email);
        
        if (is_array($parametters) ){
       
            return new Response('success');


        }

        return new Response('ERROR', 500);

    }


    public function logout(): Response
    {
        return $this->render('visitor/index.html.twig', [
            'controller_name' => 'VisitorController',
        ]);
    }
}
