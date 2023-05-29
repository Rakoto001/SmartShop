<?php

namespace App\Controller\front\article;

use App\Services\ArticleService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{

    private $articleService;

    public function __construct(ArticleService $_articleService)
    {
        $this->articleService = $_articleService;
    }
   
    /**
     * @Route("/show/list/alls", name="front_article_list_all")
     */
    public function showAllArticles()
    {
        $max = true;
        $articles = $this->articleService->getAllArticles($max);

        return $this->render('front/article/list.html.twig', [
                                                                'articles' => $articles,
        ]);

        
    }

    /**
     * @Route("/show/{id}", name="front_article_show_one", methods={"GET"})
     */
    public function showOneArtilce(Request $request, $id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $datas = $this->articleService->findOneArticle($id, 'alls');
        $article = $datas['article'];
        $commets = $datas['comments'];

        return $this->render('front/article/single-article.html.twig', [
                                                                         'article'  => $article,
                                                                         'comments' => $commets,
                                                                         'id' => $id
        ]);
    }

    /**
     * 
     * @Route("/fo/search", name="fo_article_search")
     * @return object
     */
    public function articleSearch(Request $request)
    {
        $alls = $request->query->all();
        $articleSearchedResults = $this->articleService->searchArticles($alls);

        return $this->render('front/article/list.html.twig', [
                                                                'articles' => $articleSearchedResults,
                                                             ]
                                                            );

        # code...
    }

    /**
     * 
     * @Route("/api/search/{name}", name="front_search_one_article", methods={"GET"})
     * @return void
     */
    public function check($name)
    {
        dd($name);
    }
}