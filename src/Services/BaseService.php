<?php
namespace App\Services;

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

   /**
    * find alls 
    */
   public function getAlls()
   {

     return $this->getRepository()->findAll();
   }

   public function save($_object)
   {
     $this->manager->persist($_object);

     return $this->manager->flush();
   }
}