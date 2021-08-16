<?php
namespace App\Services;

use App\Entity\User;
use App\Services\BaseService;
use Doctrine\ORM\EntityManagerInterface;

class UserService extends BaseService
{
    private $manager;
    public const M = 'Homme';
    public const F = 'Femme';
    public const GENDER = [
                             'Male' => 'Masculin', 
                             'Female' =>  'Féminin',
                            ];
    public const ACTIVE = 1;
    public const DESACTIVE = 0;
    public const STATUS = [ 
                             self::ACTIVE => 'activated' ,
                             self::DESACTIVE => 'desactivated',
    ];

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

   /**
    * list the users from database
    */
   public function listAllUsers($_parametters)
   {
       $listUsers = $this->getAllUsers($_parametters)->getResult();
       /*
                        <th>images</th>
                        <th>Nom</th>
                        <th>Sexe</th>
                        <th>Status</th>
                        <th>Action</th>
        */
        $params =[];
       foreach($listUsers as $user){
           $params[]  = [ $user->getAvatar(),
                        $user->getUsername(),
                         $user->getGender(),
                        $user->getStatus(),
                            'delete',
                        ];
            
       } 
  
       return [
                'datas'  => $params,
                'length' => count($listUsers)
              ];
   }

   /**
    * get all users from database
    */
   public function getAllUsers($_parametters)
   {
       $limit   = $_parametters['length'];
       $offset  = $_parametters['start'];
       $offset  = $_parametters['start'];

       $query   = $this->getRepository()->setLimit($limit, $offset);
       $query   = $this->getRepository()->makeSearch($_parametters, $query);
       $query   = $this->getRepository()->makeOrder($query);
       $results = $query->getQuery();

       return $results;
   }







  
   
}