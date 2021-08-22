<?php
namespace App\Controller\back\article;

// use App\Services\ArticleService;

use App\Entity\Articles;
use App\Services\ArticleService;
// use App\Services\CommentsService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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

    /**
     * @Route("/add", name="admin_article_add")
     */
    public function add(Request $request)
    {
        $article = new Articles;
       
        $userForm = $this->createForm(UserType::class, $article);
        $userForm->handleRequest($request);

    }

    /**
     * @Route("/edit/{id}", name="admin_article_edit")
     */
    public function editArticle($id)
    {

    }
}