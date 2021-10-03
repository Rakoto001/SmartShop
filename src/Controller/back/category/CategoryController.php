<?php

namespace App\Controller\back\category;

use App\Entity\Category;
use App\Form\CategorieType;
use App\Services\CategoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/category")
 */
class CategoryController extends AbstractController
{
    private $manager;
    private $categorieService;
    const CATEGORY_PAGE = 'Catégorie';

    public function __construct(EntityManagerInterface $_manager, CategoryService $_categorieService) {
        $this->manager          = $_manager;
        $this->categorieService = $_categorieService;
    }

    /**
     * @Route("/list", name="admin_category_list")
     */
    public function list()
    {
        $action   = 'Ajout';

        return $this->render('back/category/category-list.html.twig', [
                                                                        'page'   => Category::CATEGORY_PAGE,
                                                                        'action' => $action,
                                                                       ]
                             );
    }

    /**
     * @Route("/add/new", name="category_admin_add")
     */
    public function add(Request $request)
    {
        // dump('here');die();
        $categorie = new Category;
        $page     = 'Category';
        $action   = 'Ajout';
        $categorieForm = $this->createForm(CategorieType::class, $categorie);
        $categorieForm->handleRequest($request);
        // $currentUser = $user = $this->get('security.token_storage')->getToken()->getUser();
         if ($categorieForm->isSubmitted() && $categorieForm->isValid()) {
            $parametters['categorieAvatar'] = $request->files->get('avatar');
            // $imageFile = $this->articleService->checkArticle($parametters);
            // $article->setCoverImage($imageFile);
            // $article->setCreatedBy($currentUser);
            $categorie->setAvatar($request->files->get('avatar'));
            $this->manager->persist($categorie);
            $this->manager->flush();
            $this->addFlash('success', $page. ' ajouté');

            return $this->redirectToRoute('admin_category_list');
         }

         return $this->render('/back/category/category-action.html.twig', [
                                                                            'page'          => $page,
                                                                            'action'        => $action,
                                                                            'categorieForm' => $categorieForm->createView()
                                                                         ]);
    }


    /**
     * admin_category_edit
     * @Route("/edit/{id}", name="admin_category_edit")
     */
    public function edit(int $id, Request $request)
    {
        $action = 'Edition';
        $oCategorie = $this->categorieService->getOneById($id);

        $categorieForm = $this->createForm(CategorieType::class, $oCategorie);
        $categorieForm->handleRequest($request);
        // $currentUser = $user = $this->get('security.token_storage')->getToken()->getUser();
         if ($categorieForm->isSubmitted() && $categorieForm->isValid()) {
            $parametters['categorieAvatar'] = $request->files->get('avatar');
            // $imageFile = $this->articleService->checkArticle($parametters);
            // $article->setCoverImage($imageFile);
            // $article->setCreatedBy($currentUser);
            $categorie->setAvatar($request->files->get('avatar'));
            $this->manager->persist($oCategorie);
            $this->manager->flush();
            $this->addFlash('success', self::CATEGORY_PAGE. ' bien modifiée');

            return $this->redirectToRoute('admin_category_list');
         }

         return $this->render('/back/category/category-action.html.twig', [
                                                                            'page'          => self::CATEGORY_PAGE,
                                                                            'action'        => $action,
                                                                            'categorieForm' => $categorieForm->createView()
                                                                        ]);


        
    }

    
}