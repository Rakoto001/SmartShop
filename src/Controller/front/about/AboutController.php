<?php

namespace App\Controller\front\about;

use App\Entity\About;
use App\Repository\AboutRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AboutController extends AbstractController
{
    /**
     * @Route("smartShop/about", name="smart_shop_about")
     */
    public function aboutSmart(AboutRepository $about)
    {
        // dd($about->findAll());
        
        return $this->render('front/about/about.html.twig', [
                                                                'about' => $about->findAll()[0],
                                                             ]);
    }

    /**
     * @Route("/smartShop/logout", name="front_logout")
     *
     * @return void
     */
    public function fontLogout()
    {
        dd($this->get('security.token_storage')->getToken()->getUser());
        return $this->redirectToRoute('front_main');
    }
}