<?php


namespace App\Controller\front\add;

use App\Entity\User;
use App\Services\AddService;
use App\Services\ArticleService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
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
                                SessionInterface $_session
                                )
    {
        $this->articlesService = $_articlesService;
        $this->addService      = $_addService;
        $this->session         = $_session;
    }

    /**
     * @Route("/adds/add/new/{id}/{quantity}", name="adds_new_cart")
     */
    public function addNewCart(SessionInterface $session, $id, Request $request, $quantity)
    {
        /** supprimé a cause de la redirection : 
         * @ IsGranted("ROLE_USER", message="WARNING:PAGE ERROR - ACCES NON AUTORISE")
         * 
         */
        $alls = $request->request->all();
        //le panier
        $cart = $session->get('cart', []);
        $currentCustomer = $this->container->get('security.token_storage')->getToken()->getUser();
      
        $cart = $this->addService->addTocart($alls, $cart, $id);

        // si user non connnecté : redirect to login
        if (!$currentCustomer instanceof User) {

            $session->set('dataRedirection', ['id' => $id, 
            'quantity' => $quantity]);
          
            $this->addFlash('notice', 'Vous devriez vous vonnecter avant');

            $response = new JsonResponse(array(
                'route' => $this->get('router')->generate('front_login'),
                'message'    => 'Vous devriez vous vonnecter avant'));
            
            return $response;

            
        }
        $session->set('cart', $cart);
        $this->addFlash('success', 'Votre article a été ajouté dans le panier');

        return $this->redirectToRoute('front_main');
    }

     /**
     * @Route("/adds/edit/{id]/{quantity}", name="adds_edit_list")
     */
    public function FunctionName(int $id, int $quantity)
    {

        dd('adns la redir');

        
        return $this->redirectToRoute('adds_new_cart', [
            'id' => $id,
            'quantity' => $quantity,

        ]);
    }


    /**
     * @Route("/adds/list", name="adds_list")
     */
    public function listAddCart(Request $request)
    {
        // dd($request->getSession());
        // // dd($session->all());
        // // $targetPath = $targetPath->getTargetPath($request->getSession(), $providerKey = '_security.main.target_path');
        // // $targetPath = $request->getSession()->get('_security.'.'main'.'.target_path');
        // $url = $this->getTargetPath($request->getSession(), 'main');
        // dd($url);
        // FOSUserEvents::

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