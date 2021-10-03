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

   /**
    * delete role
    */
   public function remove($_id)
   {
    $roles = $this->getRole($_id);
    if (count($roles)>1) {
        foreach($roles as $role){
            // $this->removeDatas($role);
        }
    } else {
        // $this->removeDatas($roles[0]);
        // $this->removeDatas($roles);
    }

       return true;
   }

}