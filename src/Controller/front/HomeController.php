<?php
namespace App\Controller\front;

use App\Services\ArticleService;
use App\Repository\ArticlesRepository;
use App\Services\CategoryService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Cache\CacheInterface;

class HomeController extends AbstractController
{
    private $articleService;
    private $categorie;

    public function __construct(ArticleService $_articleService, CategoryService $_categorie)
    {
        $this->articleService = $_articleService;
        $this->categorie = $_categorie;
    }


    /**
     * @Route("/", name="front_main")
     */
    public function index(SessionInterface $_session, CacheInterface $cache)
    {
        $max = false;
        // $articles = $this->articleService->getAllArticles($max);

        $articles = $cache->get('articles_home', function() use($max){

            return $this->articleService->getAllArticles($max);

        });
        $categories = $this->categorie->getAllCategories();
        
        $articlesInSesion = $_session->get('article');
        // dd($articlesInSesion);

        return $this->render('/front/base-fo.html.twig', [
                                                         'articles'   => $articles,
                                                         'categories' => $categories,
                                                         ]);
    }

}