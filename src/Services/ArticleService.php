<?php
namespace App\Services;

use App\Entity\Articles;
use App\Services\BaseService;
use Doctrine\ORM\EntityManagerInterface;

class ArticleService extends BaseService
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







  
   
}