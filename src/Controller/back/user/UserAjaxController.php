<?php

namespace App\Controller\back\user;

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

    public function __construct(UserService $_userService) {
        $this->userService = $_userService;
    }
    /**
     * @Route("/ajax/list", name="admin_user_ajax_list")
     */
    public function userList(Request $request)
    {
        $allParametters = $request->request->all();
        // dd($allParametters); 
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
                                    'recordsFiltered' => $total,
                                    'draw'            => $draw,
                                ]);
    }
}