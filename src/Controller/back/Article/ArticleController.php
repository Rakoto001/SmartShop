<?php
namespace App\Controller\back\Article;

// use App\Services\ArticleService;

use App\Services\ArticleService;
// use App\Services\CommentsService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ArticleController extends AbstractController
{
    protected $articleService;
    public function __construct(ArticleService $_articleService)
    {
        $this->articleService = $_articleService;
    }

    /**
     * @Route("/admin/article/list", name="article_list")
     */
    public function listFront()
    {
        // $articles = $this->articleService->listAllArticles();
        $articles = $this->articleService->listAllArticles();

        return $this->render('back/article/list.html.twig', [
                                                            'articles' => $articles,
        ]);
        
    }
}