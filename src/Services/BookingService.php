<?php
namespace App\Services;

use App\Entity\Booking;
use App\Entity\Comments;
use App\Services\BaseService;
use Doctrine\ORM\EntityManagerInterface;

class BookingService extends BaseService
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
       return $this->manager->getRepository(Booking::class);
   }
   
  
   public function getOneById($id)
   {
       return $this->findOneById($id);
   }
  
   /**
    * get booking created by user id
    */
    public function getArticleCreatedByUser($_idArticle)
    {
 
       return $this->getRepository()->findArticleByUser($_idArticle);
     }

   /**
    * get All articles by one category
    */
   public function getAllBookerByArticleId($_id)
   {
       
      return $this->getRepository()->findAllArticlesByArticleId($_id);
   }

   /**
    * get the category actif
    */
   public function getActifCategory($_status)
   {
      return $this->getRepository()->findAllActif($_status);
   }

   public function getBooker(Type $var = null)
   {
       # code...
   }


  
   
}