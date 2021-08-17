<?php
namespace App\Services;

use App\Entity\User;
use App\Entity\Roles;
use App\Services\BaseService;
use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService extends BaseService
{
    protected $manager;
    private $kernel;
    private $container;
    private $upload;
    private $encoder;

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



   public function __construct(EntityManagerInterface $_manager,
                               KernelInterface  $_kernel,
                               ContainerInterface $_container,
                               UploadService $_upload,
                               UserPasswordEncoderInterface $_encoder
                               )
   {
       $this->manager   = $_manager;
       $this->kernel    = $_kernel;
       $this->container = $_container;
       $this->upload    = $_upload;
       $this->encoder   = $_encoder;
   }
   
   /**
    * get th repos
    */
   public function getRepository()
   {
       return $this->manager->getRepository(User::class);
   }

   /**
    * check user for registratin of for update
    */
   public function checkUser($_parametters)
   {
        $user = new User;
        $projectDir = $this->kernel->getProjectDir();
        $userImagePath = $this->container->get('kernel')->getRootDir();
        $uploadPaht = $this->container->getParameter('user_avatar_upload_path');
        $path = $projectDir.$uploadPaht;
        unset($_parametters['_token']);
        $userName    = $_parametters['username'];
        $lastName    = $_parametters['lastname'];
        $email       = $_parametters['email'];
        $gender      = $_parametters['gender'];
        $status      = $_parametters['status'];
        $roles       = $_parametters['role'];
        $imageAvatar = $_parametters['userAvatar'];

        $password = isset($_parametters['password']) ? $_parametters['password'] : '123456';

        //traitement pour le userAvatar
        $this->upload->makePath($path);
        if (isset($imageAvatar) && !empty($imageAvatar)) {
            $fileName = $this->upload->upload($path, 'userAvatar');
            $user->setAvatar($fileName);
        }

        $user->setUsername($userName)
             ->setLastname($lastName)
             ->setEmail($email)
             ->setGender($gender)
             ->setStatus($status)
             ->setPassword($this->encoder->encodePassword($user, $password));
        foreach($roles  as $role){
            $userRole = new Roles;
            $userRole->setTitle($role)
                     ->setUsers($user);
            $this->manager->persist($userRole);                                                                                  
        }

       
        $this->save($user);

        return $user;


       

        // $this->upload->upload(())
            /**
         * Grâce aux dépendances de câblage automatique de Symfony 4, i
         * l sera automatiquement injecté dans votre classe et vous pourrez y accéder en faisant :
         * $this->appKernel->getProjectDir();
         */

        
       
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