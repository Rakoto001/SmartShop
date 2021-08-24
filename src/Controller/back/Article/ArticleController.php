<?php
namespace App\Controller\back\article;

// use App\Services\ArticleService;

use App\Entity\Articles;
use App\Form\ArticleType;
// use App\Services\CommentsService;
use App\Services\ArticleService;
use App\Services\CategoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/article")
 */
class ArticleController extends AbstractController
{
    protected $articleService;
    private $manager;


    public function __construct(ArticleService $_articleService,
                                EntityManagerInterface $_manager)
    {
        $this->articleService = $_articleService;
        $this->manager = $_manager;
    }

    /**
     * @Route("/list", name="admin_article_list")
     */
    public function listArticle()
    {
        $page     = 'Article';
        $action   = 'Liste'; 
        return $this->render('back/article/article-list.html.twig', [
                                                                        'page'   => $page,
                                                                        'action' => $action,
                                                            
        ]);
        
    }

    /**
     * @Route("/add", name="admin_article_add")
     */
    public function add(Request $request)
    {
        $article = new Articles;
        $page     = 'Article';
        $action   = 'Ajout';
        $articleForm = $this->createForm(ArticleType::class, $article);
        $articleForm->handleRequest($request);
        $currentUser = $user = $this->get('security.token_storage')->getToken()->getUser();
         if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $parametters['coverImage'] = $request->files->get('coverImage');
            $imageFile = $this->articleService->checkArticle($parametters);
            $article->setCoverImage($imageFile);
            $article->setCreatedBy($currentUser);
            $this->manager->persist($article);
            $this->manager->flush();
            $this->addFlash('success', 'Article ajouté');

            return $this->redirectToRoute('admin_article_list');
         }

        return $this->render('back/article/article-action.html.twig', [
                                                                        'articleForm' => $articleForm->createView(),
                                                                        'page'        =>$page,  
                                                                        'action'      => $action,
                                                                    ]
        );

    }

    /**
     * @Route("/edit/{id}", name="admin_article_edit")
     */
    public function editArticle($id, Request $request)
    {
        $page     = 'Article';
        $action   = 'Ajout';
        $article = $this->articleService->findOne($id);
        $articleForm = $this->createForm(ArticleType::class, $article);
        $articleForm->handleRequest($request);
        $currentUser = $user = $this->get('security.token_storage')->getToken()->getUser();
         if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $parametters['coverImage'] = $request->files->get('coverImage');
            $imageFile = $this->articleService->checkArticle($parametters);
            $article->setCoverImage($imageFile);
            $article->setCreatedBy($currentUser);
            $this->manager->persist($article);
            $this->manager->flush();
            $this->addFlash('success', 'Article ajouté');

            return $this->redirectToRoute('admin_article_list');
         }

        return $this->render('back/article/article-action.html.twig', [
                                                                        'articleForm' => $articleForm->createView(),
                                                                        'page'        =>$page,  
                                                                        'action'      => $action,
                                                                    ]
        );

    }
}