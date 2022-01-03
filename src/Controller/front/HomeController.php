<?php
namespace App\Controller\front;

use App\Services\ArticleService;
use App\Repository\ArticlesRepository;
use App\Services\CategoryService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

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
    public function index(SessionInterface $_session)
    {
        $max = false;
        $articles = $this->articleService->getAllArticles($max);
        $cache = new FilesystemAdapter;

        // $articles = $cache->get('articles_home', function() use($max){

        //     return $this->articleService->getAllArticles($max);

        // });
        $categories = $this->categorie->getAllCategories();
        // mise en cache de la categorie
        // $categories = $cache->get('categories_home', function(ItemInterface $item){
        // /** @var ItemInterface  */
        //     // permet de mettre l'expiration par expireAt(date) ou item->expire

        //     // $item->expiresAfter(3600);1h
        //     return $this->categorie->getAllCategories();
        // });
        
        $articlesInSesion = $_session->get('article');
        // dd($articlesInSesion);

        return $this->render('/front/base-fo.html.twig', [
                                                         'articles'   => $articles,
                                                         'categories' => $categories,
                                                         ]);
    }

}