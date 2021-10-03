<?php
namespace App\Controller\back\category;

use App\Services\CategoryService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryAjaxController extends AbstractController
{
    /** for categ Service  */
    private $categorieService;

    public function __construct(CategoryService $_categorieService) {
        $this->categorieService = $_categorieService;
    }
    /**
     * @Route("/admin/category/datatable-list", name="admin_category_ajax_list")
     */
    public function listCategoryOnTable(Request $request)
    {
        $allParametters = $request->request->all();
        $parametters['order']  = $allParametters['order'];
        $parametters['start']  = $allParametters['start'];
        $parametters['length'] = $allParametters['length'];
        $parametters['search'] = $allParametters['search'];
        $parametters['page']   = $allParametters['page'];
        $draw      = $allParametters['draw'];

        //liste al users from database to json
        $aListcategories = $this->categorieService->listAllCategories($parametters);
        $total           = count($aListcategories);
        // $users = $listUsers['datas'];
        // $lengh = $listUsers['length'];
        return new JsonResponse([
                                  'data'         => $aListcategories,
                                  'recordsTotal' => $total
                                ]
                              );
    }

    /**
     * @Route("/delete/{id}", name="admin_category_delete")
     */
    public function delete(int $id)
    {
        
    }
}