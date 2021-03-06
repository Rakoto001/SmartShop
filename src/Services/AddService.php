<?php

namespace App\Services;

use App\Services\BaseService;
use App\Services\ArticleService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AddService extends BaseService
{
    private $articlesService;
    private $session;

    public function __construct(ArticleService $_articlesService, SessionInterface $session) {
        $this->articlesService = $_articlesService;
        $this->session = $session;
    }

    /**
     * add article to cart
     */
    public function addTocart($alls, $cart, $id)
    {
        if (!empty($cart[$id])) {
            $cart[$id] ++;
        } else {
            // /condition si l'article est  dans la DB ou non
            if (!empty($alls)) {
                $articleQuantity = (int)$alls['product-quanity'];
            } else {
                $articleQuantity = 1;
            }

            $cart[$id] = $articleQuantity;
        }

        return $cart;
    }

    /**
     * get the article and a total price
     */
    public function getArticlePrice($_cart)
    {
        $tmp_price    = [];
        $cartArticles = [];
        $totalPrice = '';

        if (isset($_cart)) {
            foreach($_cart as $articleId => $numberOfArticle){
                // $tmp_price[] = $this->articlesService->findOneArticle($articleId)->getPrice() * $numberOfArticle;
                $tmp_price[] = $this->articlesService->findOneArticle($articleId, 'article')->getPrice() * $numberOfArticle;
            }
            $totalPrice = array_sum($tmp_price);
            //pour les articles
            foreach($_cart as $articleId => $nOfArticle ){
               $cartArticles[] = [
                                'article'  => $this->articlesService->findOneArticle($articleId, 'article'),
                                'quantity' => $nOfArticle,
               ];
            }
        }
        

        return ['totalPrice' => $totalPrice, 'cartArticles' => $cartArticles];
    }

    
    /**
     * remove all session stored in cart
     *
     * @return true
     */
    public function removeSessionAction()
    {
        $cart = $this->session->set('cart', []);

        return true;
    }

}