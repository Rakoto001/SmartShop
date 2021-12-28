<?php

namespace App\Event\Listener;

use DateTime;
use Swift_Mailer;
use App\Services\AddService;
use App\Event\TechnoBuyEvent;
use App\Services\MailerService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class TechnoEventListener
{
    private $session;
    private $addService;
    private $mailerService;

    public function __construct(MailerService  $mailer, SessionInterface $session, AddService $addService) {
        $this->mailerService = $mailer;
        $this->session           = $session;
        $this->addService = $addService;
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
        $this->mailerService->sendMailToAdmin($paramsArticles, $purshaseDate);

        return true;
    }

}