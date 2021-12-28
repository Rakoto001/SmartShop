<?php

namespace App\Controller\front\buy;

use DateTime;
use App\Services\AddService;
use App\Event\TechnoBuyEvent;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BuyController extends AbstractController
{

    private $session;
    private $addService;

    public function __construct(SessionInterface $session, AddService $addService) {
        $this->session           = $session;
        $this->addService = $addService;
    }

    /**
     * @Route("/articles/buy", name="article_buy")
     * @IsGranted("ROLE_USER", message="WARNING - ACCES REFUSE")
     */
    public function buyArticles(EventDispatcherInterface $dispatcher)
    {
        $dispatcher->dispatch('send.mail.action',new TechnoBuyEvent);

        $this->addFlash('success', 'Votre achat a été confirmé');

        return $this->redirectToRoute('adds_list');
    }

}