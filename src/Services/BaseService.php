<?php
namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

abstract class BaseService
{

 
    /**
     * get Entity by their ID
     */
   public function findOneById($_id)
   {
      //  dd($_id);
      return $result = $this->getRepository()->find($_id);
   }
   public function findOneBy(array $_parameters)
   {
     return  $this->getRepository()->findBy($_parameters);
   }

   /**
    * find alls 
    */
   public function getAlls()
   {

     return $this->getRepository()->findAll();
   }

   /**
    * save obj
    */
   public function save(User $_object)
   {
     $this->manager->persist($_object);

     return $this->manager->flush();
   }

   /**
    * delete object
    */
   public function removeDatas($_object)
   {
     $this->manager->remove($_object);

     return $this->manager->flush();
   }
}