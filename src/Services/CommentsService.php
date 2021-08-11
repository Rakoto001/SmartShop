<?php
namespace App\Services;

use App\Entity\Comments;
use App\Services\BaseService;
use Doctrine\ORM\EntityManagerInterface;

class CommentsService extends BaseService
{
    protected $manager;
    private $articleService;
    private $userService;

   public function __construct(EntityManagerInterface $_manager,
                                ArticleService $_articleService,
                                UserService $_userService)
   {
       $this->manager = $_manager;
       $this->articleService = $_articleService;
       $this->userService = $_userService;
   }
   
   /**
    * get th repos
    */
   public function getRepos()
   {
       return $this->manager->getRepository(Comments::class);
   }

   /**
    * findOne comment
    */
   public function getOneById($_id)
   {

       return $this->findOneById($_id);
   }

   /**
    * check if comment already exists
    */
   public function checkComment($_userId, $_articleId)
   {

       return $this->getRepos()->findCommmentByCriteria($_userId, $_articleId);
    }
  
   /**
    * save the comment
    */
   public function saveComment($_message, $_idArticle, $_idUser)
   {
    $comments = $this->checkComment($_idUser, $_idArticle);

    if ($comments) {
        
        return $comments;
    }

    $comment    = new Comments();
    $articleObj = $this->articleService->findOne( $_idArticle);
    $userObj    = $this->userService->findOne($_idUser);
    $comment->setRating(mt_rand(0,10))
            ->setContent($_message)
            ->setAuthor($userObj)
            ->setArticle($articleObj);
            
    return $this->save($comment);
   }






  
   
}