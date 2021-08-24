<?php
namespace App\Services;

use App\Entity\Category;
use App\Entity\Comments;
use App\Services\BaseService;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService extends BaseService
{
    protected $manager;
    private $articleService;
    private $userService;

   public function __construct(EntityManagerInterface $_manager,
                                ArticleService $_articleService,
                                UserService $_userService)
   {
       $this->manager = $_manager;
       $this->articleService = $_articleService;
       $this->userService = $_userService;
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



  
   
}