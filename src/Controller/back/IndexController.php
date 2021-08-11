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
     * @Route("/admin/main", name="admin_main")
     */
    public function baseMain()
    {
        $annonce = $this->articleService->findOne(27);
        // dd('ici');

        return $this->render('back/main.html.twig');
    }

}