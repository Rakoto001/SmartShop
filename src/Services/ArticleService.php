<?php
namespace App\Services;

use App\Entity\Articles;
use App\Services\BaseService;
use App\Services\UploadService;
use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ArticleService extends BaseService
{
    protected $manager;
    private $_router;
    private $kernel;
    private $container;
    private $upload;



    const ACTIVATE      = 'Activé';
    const DESACTIVATE   = 'Desactivé';
    const STATUS_OPTION = [
                                    '1'    => self::ACTIVATE,
                                    '0' => self::DESACTIVATE,
    ];

    
   public function __construct(EntityManagerInterface $_manager,
                               UrlGeneratorInterface $_router,
                               KernelInterface $_kernel,
                               ContainerInterface $_container,
                               UploadService $_upload) 
   {
       $this->manager = $_manager;
       $this->router  = $_router;
       $this->kernel  = $_kernel;
       $this->container = $_container;
       $this->upload = $_upload;
   }
   
   /**
    * get th repos
    */
   public function getRepository()
   {
       return $this->manager->getRepository(Articles::class);
   }

   /**
    * findOne articles
    */
   public function findOne($_id)
   {

       return $this->findOneById($_id);
   }

   /**
    * list all articles
    */
   public function getAllArticles($_max)
   {
       return $this->getRepository()->findAllArticles($_max);
   }

   /**
    * find one article & comments by their iD
    * return obj
    */
    public function findOneArticle($_id, $_keys)
    {
        $article = $this->getRepository()->findOneBy (['id' => $_id]);
        //si c'est une requette qui demande l'article avec ces commentaires
        if ($_keys == 'alls') {
            $comments = $this->getRepository()->findAllComments($_id);

            return ['article' => $article, 'comments' => $comments];
        }

        
        return  $article;
       
    }

    /**
     * add or edit article
     */
    public function checkArticle($_parametters)
    {
        $projectDir = $this->kernel->getProjectDir();
        $articleUploadPath = $this->container->getParameter('article_cover_upload_path');
        $path = $projectDir.$articleUploadPath;
        $coverImage = $_parametters['coverImage'];
         //traitement pour le userAvatar
         $this->upload->makePath($path);
         if (isset($coverImage) && !empty($coverImage)) {
             $fileName = $this->upload->upload($path, 'coverImage');
            //  $user->setAvatar($fileName);

            return $fileName;
         }
        
    }

    /**
     * list articles from database
     */
    public function listAllArticles(array $_parametters)
    {
        $listArticles = $this->getArticles($_parametters)->getResult();
        /*
                       <th>Logo</th>
												<th>Nom</th>
												<th>Contenu</th>
												<th>Status</th>
												<th>Action</th>
         */
         $params =[];
        foreach($listArticles as $article){
             // pour l'avatar
            if (empty($article->getCoverImage())) {
                $imageCover = '<img class="img-circle " src="/bo/upload/avatar_default.png" style="width:40px;" alt="User Image">';
              } else {
                $imageCover = '<img class="img-circle " src="/upload/bo/article/'.$article->getCoverImage().'" style="width:40px;" alt="User Image">';
              }
             //btnAction delete 
             $id = $article->getId();
             // $this->router->generate('admin_delete_user', $id);
             $btnEdit   = '<a href="'.$this->router->generate('admin_article_edit', ['id' => $id]).'"><i class="ti-pencil-alt"></i> </a>';
             $btnDelete = '<a href="'.$this->router->generate('admin_article_delete', ['id' => $id]).'"><i class="ti-trash"></i> </a>';
             $btnAction = $btnEdit.$btnDelete;
             $params[]  = [
                           $imageCover,
                           // $user->getAvatar(),
                           $article->getName(),
                           $article->getContent(),
                           $article->getStatus(),
                           $btnAction,
                         ];
             
        } 
   
        return [
                 'datas'  => $params,
                 'length' => count($listArticles)
               ];

    }

    /**
    * get all articles from database
    */
   public function getArticles($_parametters)
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
    * delete article object
    */
   public function remove($_object)
   {
       $projectDir = $this->kernel->getProjectDir();
       $articleUploadPath = $this->container->getParameter('article_cover_upload_path');
       $coverImage = $_object->getCoverImage();

       $fileContent = $projectDir.$articleUploadPath.'/'.$coverImage;
       if (file_exists($fileContent)) {
          unlink($fileContent);
       }
       
       return $this->removeDatas($_object);
   }

   /**
    * search all articles by values from input
    */
   public function searchArticles(array $_articleParamsValues)
   {
       $searchParams = $_articleParamsValues['article-search'];
       $articles = $this->getRepository()->findByCriterias($searchParams);

       return $articles;
   }







  
   
}