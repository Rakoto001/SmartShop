<?php
namespace App\Services;

use App\Entity\Category;
use App\Entity\Comments;
use App\Services\BaseService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CategoryService extends BaseService
{
    protected $manager;
    private $articleService;
    private $userService;
    private $router;
    const ACTIVE    = 1;
    const DESAVTIVE = 0;
    const STATUS_OPTION = [ 
                            'Activé'    => self::ACTIVE,
                            'Désactivé' => self::DESAVTIVE
    ];

   public function __construct(EntityManagerInterface $_manager,
                                ArticleService $_articleService,
                                UserService $_userService,
                                UrlGeneratorInterface $_router)
   {
       $this->manager        = $_manager;
       $this->articleService = $_articleService;
       $this->userService    = $_userService;
       $this->router         = $_router;
   }

   public function getRepository()
   {
       return $this->manager->getRepository(Category::class);
   }
   
  
   public function getOneById($id)
   {
       return $this->findOneById($id);
   }
   /**
    * get all th categories
    */
   public function getAllCategories()
   {
      return $this->getAlls();
   }

   /**
    * get All articles by one category
    */
   public function getAllArticlesById($_id)
   {
       
      return $this->getRepository()->findAllArticlesByCategId($_id);
   }

   /**
    * get the category actif
    */
   public function getActifCategory($_status)
   {
      return $this->getRepository()->findAllActif($_status);
   }

   /**
    * list all categs 
    */
   public function listAllCategories(array $_parametters = [])
   {
    $aListCategories = $this->getCategories($_parametters)->getResult();
    $paramsoutPut = [];
    $lenth = count($aListCategories);
                                                /*<th>Couverture</th>
												<th>Nom</th>
												<th>Description</th>
												<th>Status</th>
                                                <th>Action</th>
                                                */
    /** output pour js datatable */
    foreach ($aListCategories as $key => $categorie) {
        // UrlGeneratorInterface $this->router
        // $btnEdit   = '<a href="'.$this->router->generate('admin_user_edit', ['id' => $id]).'"><i class="ti-pencil-alt"></i> </a>';

        $editRoute   = '<a href="'.$this->router->generate('admin_category_edit', ['id' => $categorie->getId()]).'"> <i class="ti-pencil-alt"> </i></a>';
        $deleteRoute = '<a href="'.$this->router->generate('admin_category_delete', ['id' => $categorie->getId()]).'"> <i class="ti-trash"></i> </a>'; 
        $action = $editRoute.$deleteRoute;
        $paramsoutPut []= [
                             $categorie->getAvatar(),
                             $categorie->getName(),
                             $categorie->getDescription(),
                             $categorie->getStatus(),
                             $action
                        ];
        
    }

    return $paramsoutPut;
       
   }

   /**
    * get all the categories with criterias from datatables
    */
   public function getCategories(array $_parametters)
   {
    $limit   = $_parametters['length'];
    $offset  = $_parametters['start'];
    $offset  = $_parametters['start'];
    $query   = $this->getRepository()->setLimit($limit, $offset);
    $query   = $this->getRepository()->makeSearch($_parametters, $query);
    $query   = $this->getRepository()->makeOrder($query);
    $results = $query->getQuery();
    
    return $results;    # code...
   }



  
   
}