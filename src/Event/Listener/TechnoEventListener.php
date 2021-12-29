<?php

namespace App\Event\Listener;

use DateTime;
use Swift_Mailer;
use App\Services\AddService;
use App\Event\TechnoBuyEvent;
use App\Services\MailerService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class TechnoEventListener
{
    private $session;
    private $addService;
    private $mailerService;
    private $container;

    public function __construct(MailerService  $mailer, SessionInterface $session, AddService $addService, ContainerInterface $container) {
        $this->mailerService = $mailer;
        $this->session           = $session;
        $this->addService = $addService;
        $this->container = $container;
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

}