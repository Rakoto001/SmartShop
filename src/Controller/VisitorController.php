<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VisitorController extends AbstractController
{
    /**
     * @Route("/login", name="visitor_login")
     */
    public function login(): Response
    {
        return $this->render('front/login.html.twig', [
            
        ]);
    }

     /**
     * @Route("/logout", name="visitor_logout")
     */
    public function logout(): Response
    {
        return $this->render('visitor/index.html.twig', [
            'controller_name' => 'VisitorController',
        ]);
    }
}
