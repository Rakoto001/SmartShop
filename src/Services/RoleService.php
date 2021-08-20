<?php
namespace App\Services;

use App\Entity\Roles;
use App\Services\BaseService;
use Doctrine\ORM\EntityManagerInterface;

class RoleService extends BaseService
{
    protected $manager;
    
    public function __construct(EntityManagerInterface $_manager)
     {
         $this->manager = $_manager;
     }
     /**
    * get the repos
    */
   public function getRepository()
   {
       return $this->manager->getRepository(Roles::class);
   }

   /**
    * get title role
    */
   public function getRole($id)
   {
      return $this->getRepository()->findAllRoles($id);
   }

}