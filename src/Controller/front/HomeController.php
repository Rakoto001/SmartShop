<?php
namespace App\Controller\front;

use App\Services\ArticleService;
use App\Repository\ArticlesRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $articleService;

    public function __construct(ArticleService $_articleService)
    {
        $this->articleService = $_articleService;
    }


    /**
     * @Route("/smartshop/main", name="front_main")
     */
    public function index(SessionInterface $_session)
    {
        $max = false;
        $articles = $this->articleService->getAllArticles($max);
        $articlesInSesion = $_session->get('article');
        // dd($articlesInSesion);

        return $this->render('/front/base-fo.html.twig', [
                                                         'articles' => $articles,
                                                         ]);
    }

}