<?php
namespace App\Controller\back\article;

// use App\Services\ArticleService;

use App\Services\ArticleService;
// use App\Services\CommentsService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/admin/article")
 */
class ArticleController extends AbstractController
{
    protected $articleService;
    public function __construct(ArticleService $_articleService)
    {
        $this->articleService = $_articleService;
    }

    /**
     * @Route("/list", name="admin_article_list")
     */
    public function listArticle()
    {
        $page     = 'Article';
        $action   = 'Liste'; 
        return $this->render('back/article/article-list.html.twig', [
                                                                        'page'   => $page,
                                                                        'action' => $action,
                                                            
        ]);
        
    }
}