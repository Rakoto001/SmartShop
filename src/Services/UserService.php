<?php
namespace App\Services;

use App\Entity\User;
use App\Entity\Roles;
use App\Services\BaseService;
use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService extends BaseService
{
    protected $manager;
    private $kernel;
    private $container;
    private $upload;
    private $encoder;
    private $router;
    private $roleService;
    private $mailer;

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
                               MailerService $_mailer,
                               UserPasswordEncoderInterface $_encoder,
                               UrlGeneratorInterface $_router,
                               RoleService $_roleService
                               )
   {
       $this->manager     = $_manager;
       $this->kernel      = $_kernel;
       $this->container   = $_container;
       $this->upload      = $_upload;
       $this->encoder     = $_encoder;
       $this->router      = $_router;
       $this->roleService = $_roleService;
       $this->mailer      = $_mailer;
   }
   
   /**
    * get th repos
    */
   public function getRepository()
   {
       return $this->manager->getRepository(User::class);
   }

   public function getCurrentUser()
   {
       $currentValues = $this->container->get('security.token_storage')->getToken()->getUser();
       if( $currentValues instanceof User) {

        return $currentValues;
       }

        return null;
   }

   /**
    * check user for registratin of for update
    */
   public function checkUser($_parametters, User $user)
   {
        $user = new User;
        $projectDir = $this->kernel->getProjectDir();
        $userImagePath = $this->container->get('kernel')->getRootDir();
        $uploadPaht = $this->container->getParameter('user_avatar_upload_path');
        $path = $projectDir.$uploadPaht;
        unset($_parametters['_token']);
        $id          = isset($_parametters['id']) ? $_parametters['id'] : '0';
        $userName    = $_parametters['username'];
        $lastName    = $_parametters['lastname'];
        $email       = $_parametters['email'];
        $gender      = $_parametters['gender'];
        $status      = $_parametters['status'];
        $roles       = $_parametters['role'];
        $imageAvatar = $_parametters['userAvatar'];
        $password = isset($_parametters['password']) ? $_parametters['password'] : '123456';
       
        //si user existe déjà
        if (!empty($id)) {
           return $this->makeEditUser($id, $roles, $path, $imageAvatar);
        }

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
    * edit user and save
    */
   public function makeEditUser($id, $roles, $path, $imageAvatar)
   {
        $user  = $this->findOne($id);
        //traitement pour le userAvatar
         $this->upload->makePath($path);
         if (isset($imageAvatar) && !empty($imageAvatar)) {
             $fileName = $this->upload->upload($path, 'userAvatar');
             $user->setAvatar($fileName);
         }
        $objRoles = $this->roleService->getRole($id);
        if (count($roles)>1 && count($objRoles)>1) {
            foreach($objRoles as $key => $newRole){
                if ($newRole->getTitle() != $roles[$key]) {
                        $newRole->setTitle($roles);
                    }
                } 
            $this->save($newRole);

        } elseif(count($objRoles)>1 && count($roles)==1) {
            //SUPRIME l'un
            $this->removeDatas($objRoles[0]);
            //    mofifie l'autre
            $newRole = $objRoles[1]->setTitle($roles[0]);
            $this->save($newRole);

        } elseif(count($objRoles) == 1 && count($roles) == 1){
            $newRole = $objRoles[0]->setTitle($roles[0]);

            $this->save($newRole);

        } elseif(count($objRoles) == 1 && count($roles) > 1){
            $newRole = $objRoles[0]->setTitle($roles[0]);
            $this->save($newRole);
            $newRole = new Roles;
            $newRole->setTitle($roles[1])
                    ->setUsers($user);
            $this->save($newRole);
        }

        return $this->save($user);
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
            // pour l'avatar
           if (empty($user->getAvatar())) {
               $imageAvatar = '<img class="img-circle " src="/bo/upload/avatar_default.png" style="width:40px;" alt="User Image">';
             } else {
              $imageAvatar = '<img class="img-circle " src="/upload/bo/user/'.$user->getAvatar().'" style="width:40px;" alt="User Image">';
             }
            //btnAction delete 
            $id = $user->getId();
            // $this->router->generate('admin_delete_user', $id);
            $btnEdit   = '<a href="'.$this->router->generate('admin_user_edit', ['id' => $id]).'"><i class="ti-pencil-alt"></i> </a>';
            $btnDelete = '<a href="'.$this->router->generate('admin_delete_user', ['id' => $id]).'"><i class="ti-trash"></i> </a>';
            $btnAction = $btnEdit.$btnDelete;
            $params[]  = [
                          $imageAvatar,
                          // $user->getAvatar(),
                          $user->getUsername(),
                          $user->getGender(),
                          $user->getStatus(),
                          $btnAction,
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

   /**
    * remove user
    */
   public function remove($_object)
   {
    $projectDir = $this->kernel->getProjectDir();
    $articleUploadPath = $this->container->getParameter('user_avatar_upload_path');
    $avatar = $_object->getAvatar();

    $fileContent = $projectDir.$articleUploadPath.'/'.$avatar;
    if (file_exists($fileContent)) {
       unlink($fileContent);
    }

    return $this->removeDatas($_object);
   }

   /**
    * get article created by user id
    */
   public function getArticleCreatedByUser($_id)
   {

      return $this->getRepository()->findArticleByUser($_id);
    }


    public function registerNewUser($aParamsUserRegister)
    {
        $user = new User;
        //  generation password aléatoire
        $randomUserPass = bin2hex(openssl_random_pseudo_bytes(4));
        $user->setUsername($aParamsUserRegister['username'])
             ->setLastname($aParamsUserRegister['lastname'])
             ->setEmail($aParamsUserRegister['email'])
             ->setGender($aParamsUserRegister['gender'])
             ->setStatus(0)
             ->setPassword($this->encoder->encodePassword($user, $randomUserPass))
             ->setConfirmationAccount(0)
             ->setAvatar('https://randomuser.me/api/portraits/');
            
        $this->save($user);
        
        $this->mailer->sendMailToNewRegistredCustomer($aParamsUserRegister['email'], $randomUserPass);


        return $user;

    }






  
   
}