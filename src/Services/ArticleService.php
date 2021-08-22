<?php
namespace App\Services;

use App\Entity\Articles;
use App\Services\BaseService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ArticleService extends BaseService
{
    private $manager;
    private $_router;
    
   public function __construct(EntityManagerInterface $_manager,
                              UrlGeneratorInterface $_router) 
   {
       $this->manager = $_manager;
       $this->router  = $_router;
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
                $imageCover = '<img class="img-circle " src="/upload/bo/user/'.$article->getCoverImage().'" style="width:40px;" alt="User Image">';
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







  
   
}