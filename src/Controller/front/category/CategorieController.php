<?php

namespace App\Controller\front\category;

use App\Services\ArticleService;
use App\Services\CategoryService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/category")
 */
class CategorieController extends AbstractController
{

    private $categoryService;
    private $articleService;

    public function __construct(CategoryService $_categoryService, ArticleService $_articleService)
    {
        $this->categoryService = $_categoryService;
        $this->articleService = $_articleService;
    }
   
    /**
     * @Route("/show-all/{id}", name="front_categorie_article_show")
     */
    public function showAllArticleByCategorie($id)
    {
        
        $articles = $this->categoryService->getAllArticlesById($id)[0]['articles'];
        $category = $this->categoryService->getOneById($id);
        
        return $this->render('front/category/list.html.twig', [
                                                                'category' => $category,
                                                                'articles' => $articles,
        ]);
    }

   
    // public function showOneArtilce(Request $request, $id)
    // {
    //     $user = $this->get('security.token_storage')->getToken()->getUser();
    //     $datas = $this->articleService->findOneArticle($id, 'alls');
    //     $article = $datas['article'];
    //     $commets = $datas['comments'];

    //     return $this->render('front/article/single-article.html.twig', [
    //                                                                      'article'  => $article,
    //                                                                      'comments' => $commets,
    //     ]);
    // }
}