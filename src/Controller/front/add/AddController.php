<?php

namespace App\Controller\front\add;

use App\Services\AddService;
use App\Services\ArticleService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddController extends AbstractController
{
    private $articlesService;
    private $addService;
    private $session;

    public function __construct(ArticleService $_articlesService, 
                                AddService $_addService,
                                SessionInterface $_session)
    {
        $this->articlesService = $_articlesService;
        $this->addService      = $_addService;
        $this->session         = $_session;
    }

    /**
     * @Route("/adds/add/new/{id}", name="adds_new_cart")
     * @IsGranted("ROLE_USER", message="WARNING:PAGE ERROR - ACCESS DENIED")
     */
    public function addNewCart(SessionInterface $session, $id, Request $request)
    {
        $alls = $request->request->all();
        //le panier
        $cart = $session->get('cart', []);
        $cart = $this->addService->addTocart($alls, $cart, $id);
        $session->set('cart', $cart);
        $this->addFlash('success', 'Votre article a été ajouté dans le panier');

        return $this->redirectToRoute('front_main');
    }


    /**
     * @Route("/adds/list", name="adds_list")
     */
    public function listAddCart()
    {
        // dd($session->all());
        $cart = $this->session->get('cart');
        $paramsArticles = $this->addService->getArticlePrice($cart);
        $cartArticles = $paramsArticles['cartArticles'];
        $totalPrice   = $paramsArticles['totalPrice'];
        
        return $this->render('front/add/list-adds.html.twig', [
                                                                'cartArticles'  => $cartArticles,
                                                                'total'         => $totalPrice
        ]);
    }

    /**
     * remove article from the list of cart
     * @Route("/adds/delete/{id}", name="adds_remove")
     */
    public function removeArticle($id)
    {
        
        $arrayCartList = $this->session->get('cart');
        unset($arrayCartList[$id]);
        $this->session->set('cart', $arrayCartList);
        $this->addFlash('success', 'SUPRESSION REUSSITE');

        return $this->redirectToRoute('adds_list');
    }

}