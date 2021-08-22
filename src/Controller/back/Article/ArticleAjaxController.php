<?php

namespace App\Controller\back\article;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleAjaxController extends AbstractController
{
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
        $listArticles = $this->userService->listAllArticles($parametters);
        // $total     = count($listUsers);
        // $users = $listUsers['datas'];
        // $lengh = $listUsers['length'];


        return new JsonResponse([
                                    'data'            => $users, 
                                    'recordsTotal'    => $lengh,
                                    // 'recordsFiltered' => $total,
                                    // 'draw'            => $draw,
                                ]);

        
    }
}