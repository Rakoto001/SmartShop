<?php

namespace App\Controller\back\user;

use App\Services\ArticleService;
use App\Services\BookingService;
use App\Services\RoleService;
use App\Services\UserService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/user")
 */
class UserAjaxController extends AbstractController
{
    private $userService;
    private $roleService;
    private $articleService;
    private $bookingService;

    public function __construct(UserService $_userService,
                                RoleService $_roleService,
                                ArticleService $_articleService,
                                BookingService $_bookingService) {
        $this->userService    = $_userService;
        $this->roleService    = $_roleService;
        $this->articleService = $_articleService;
        $this->bookingService = $_bookingService;
    }
    /**
     * @Route("/ajax/list", name="admin_user_ajax_list")
     */
    public function userList(Request $request)
    {
        $allParametters = $request->request->all();
        $parametters['order']  = $allParametters['order'];
        $parametters['start']  = $allParametters['start'];
        $parametters['length'] = $allParametters['length'];
        $parametters['search'] = $allParametters['search'];
        $parametters['page']   = $allParametters['page'];
        $draw      = $allParametters['draw'];
        //liste al users from database to json
        $listUsers = $this->userService->listAllUsers($parametters);
        $total     = count($listUsers);
        $users = $listUsers['datas'];
        $lengh = $listUsers['length'];


        return new JsonResponse([
                                    'data'            => $users, 
                                    'recordsTotal'    => $lengh,
                                    // 'recordsFiltered' => $total,
                                    // 'draw'            => $draw,
                                ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_delete_user")
     */
    public function deleteUser($id)
    {
        // dd($id);
        //supprime les articles crée par l'utilisateur a supprimer
       $articles = $this->userService->getArticleCreatedByUser($id);
       if ($articles) {
           if (count($articles)>1) {
                foreach($articles as $article){
                    //delete article
                    // $this->articleService->remove($article);
                    
                    $booker = $this->bookingService->removeByCriterias->getID();
                    dd($booker);

                }
           } else {
                    // find booking by id of article
                    $booker = $this->bookingService->getOneById($articles[0]->getId());
                    dd($booker);


                    // $this->articleService->remove($articles[0]);
           }

       }
       //
       //suppression du role de l'utilisateur
       $this->roleService->remove($id);
       //supression de l'utilsateur
       $user  = $this->userService->findOne($id);
       $this->userService->remove($user);
       
       return $this->redirectToRoute('admin_article_list');
    }

    
}