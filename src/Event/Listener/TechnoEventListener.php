<?php

namespace App\Event\Listener;

use DateTime;
use Swift_Mailer;
use App\Services\AddService;
use App\Event\TechnoBuyEvent;
use App\Services\MailerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class TechnoEventListener
{
    private $session;
    private $addService;
    private $mailerService;
    private $container;
    private $routes;

    public function __construct(MailerService  $mailer, 
    SessionInterface $session, 
    AddService $addService, 
    ContainerInterface $container,
    UrlGeneratorInterface  $routes
    ) {
        $this->mailerService = $mailer;
        $this->session = $session;
        $this->addService = $addService;
        $this->container = $container;
        $this->routes = $routes;
    }


    /**
     * Undocumented function
     *
     * @param TechnoBuyEvent $techEvent
     * @return void
     */
    public function onSendMailAction(TechnoBuyEvent $techEvent)
    {
        $cart = $this->session->get('cart');
        $paramsArticles = $this->addService->getArticlePrice($cart);
        $cartArticles   = $paramsArticles['cartArticles'];
        $totalPrice     = $paramsArticles['totalPrice'];
        $odate          = new DateTime();
        $purshaseDate   = $odate->format('Y-m-d H:i:s');
        $currentCustomer = $this->container->get('security.token_storage')->getToken()->getUser();

        if($currentCustomer){
            $this->mailerService->sendMailToCustomer($paramsArticles, $currentCustomer, $purshaseDate);
            $this->mailerService->sendMailToAdmin($paramsArticles, $currentCustomer, $purshaseDate);
        }

        return true;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {

        // exécuté seulement si la personne est authentifiée
        // https://stackoverflow.com/questions/11180351/symfony2-after-successful-login-event-perform-set-of-actions
        // dd($event);

        // pour la redirection 
        // https://stackoverflow.com/questions/40433405/redirect-to-another-symfony-route-from-filtercontrollerevent-listener
        

        // création route
        // https://stackoverflow.com/questions/8972069/how-redirect-in-onsecurityinteractivelogin-method-in-loginlistener-symfony2
    //   $this->session->set('dataRedirection', '');
        $id =  $this->session->get('id', [])['id'];

        $response = new RedirectResponse($this->routes->generate('adds_list')); //adds_list
        return $response->send();

    

    }

}