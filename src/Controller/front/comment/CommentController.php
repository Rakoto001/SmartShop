<?php

namespace App\Controller\front\comment;

use PhpParser\Comment;
use App\Entity\Comments;
use App\Services\CommentsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    private $commentService;

    public function __construct(CommentsService $_commentService) {
        $this->commentService = $_commentService;
    }

    /**
     * @Route("article/show/all-comments/{idArticle}/{idUser}", name="comment_add_new")
     * @IsGranted("ROLE_USER")
     */
    public function saveComments(Request $request, $idArticle, $idUser)
    {
        $allDatas = $request->request->all();
        $message = $allDatas['message'];
        $comments = $this->commentService->saveComment($message, $idArticle, $idUser);
        if ($comments) {
            $this->addFlash('error', 'Vous avez déjà commenté cette article');
        } else {
        $this->addFlash('success', 'Commentaire ajouté');
        }

        return $this->redirectToRoute('front_article_show_one', [
                                                                 'id' => $idArticle
        ]);
        
    }

}