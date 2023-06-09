<?php

namespace App\Controller\front\register;

use App\Entity\User;
use App\Services\UserService;
use App\Form\RegistrationType;
use App\Services\MailerService;
use PHPMD\Renderer\JSONRenderer;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class registerController extends AbstractController
{
    private $userService;
    private $mailer;
    // protected $container;

    public function __construct(UserService $_userService, MailerService $mailer, ContainerInterface $container) {
        $this->userService = $_userService;
        $this->mailer = $mailer;
        // $this->container = $container;
    }


    
    public function registerVisitor(Request $request)
    {

        $registrationForm = $this->createForm(RegistrationType::class, new User);

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


        
        return $this->render('front/register.html.twig', ['registerForm' => $registrationForm->createView()]);
    }




    

}