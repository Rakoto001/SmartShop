<?php
namespace App\Services;

use App\Entity\User;
use App\Services\BaseService;
use Doctrine\ORM\EntityManagerInterface;

class UserService extends BaseService
{
    private $manager;
   public function __construct(EntityManagerInterface $_manager)
   {
       $this->manager = $_manager;
   }
   
   /**
    * get th repos
    */
   public function getRepository()
   {
       return $this->manager->getRepository(User::class);
   }

   /**
    * findOne user by id
    */
   public function findOne($_id)
   {
       
       return $this->findOneById($_id);
   }







  
   
}