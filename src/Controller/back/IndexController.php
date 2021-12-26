<?php

namespace App\Controller\back;

use App\Services\ArticleService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    private $articleService;

    public function __construct(ArticleService $_articleService)
    {
        $this->articleService = $_articleService;
    }

    /**
     * @Route("/admin/main", name="home")
     */
    public function baseMain()
    {
        $page = 'Accueil';
        $action = '';
        $annonce = $this->articleService->findOne(27);
       dump( $this->container->get('security.token_storage')->getToken());

        return $this->render('back/base-bo.html.twig',  [
                                                        'page'   => $page,
                                                        'action' => $action,
                                                     ]
                            );

    //                         return $this->render('back/main.html.twig',  [
    //                             'page'   => $page,
    //                             'action' => $action,
    //                          ]
    // );
        
                            
    }

}