<?php

namespace App\Controller\back\article;

use App\Services\ArticleService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleAjaxController extends AbstractController
{
    private $articleService;

    public function __construct(ArticleService $_article) {
        $this->articleService = $_article;
    }
    /**
     * @Route("/admin/article-ajax-list", name="admin_article_ajax_list")
     */
    public function listArticleData(Request $request)
    {
        $allParametters = $request->request->all();
        $parametters['order']  = $allParametters['order'];
        $parametters['start']  = $allParametters['start'];
        $parametters['length'] = $allParametters['length'];
        $parametters['search'] = $allParametters['search'];
        $parametters['page']   = $allParametters['page'];
        $draw      = $allParametters['draw'];
        //liste al users from database to json
        $listArticles = $this->articleService->listAllArticles($parametters);
        // $total     = count($listUsers);
        $articles = $listArticles['datas'];
        $lengh = $listArticles['length'];


        return new JsonResponse([
                                    'data'            => $articles, 
                                    'recordsTotal'    => $lengh,
                                    // // 'recordsFiltered' => $total,
                                    // // 'draw'            => $draw,
                                ]);

        
    }

    /**
     * @Route("/delete/{id}", name="admin_article_delete")
     */
    public function remove($id, Request $request)
    {
       $article = $this->articleService->findOne($id);
       $this->articleService->remove($article);

       return $this->redirectToRoute('admin_article_list');
    }
}