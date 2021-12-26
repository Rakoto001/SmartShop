<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class VisitorController extends AbstractController
{
  
  /**
   * @Route("/login", name="front_login")
   *
   * @return Response
   */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('front/login.html.twig', [
            
        ]);
    }

    
    public function logout(): Response
    {
        return $this->render('visitor/index.html.twig', [
            'controller_name' => 'VisitorController',
        ]);
    }
}
