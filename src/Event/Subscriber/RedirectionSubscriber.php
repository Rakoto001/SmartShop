<?php

namespace App\Event\Subscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class RedirectionSubscriber implements EventSubscriberInterface{

    use TargetPathTrait;


    private $session;
    private $router;
    public function __construct(SessionInterface $session, RouterInterface $router) {
        $this->session = $session;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        // return [
        //     // KernelEvents::VIEW => ['encoePass', EventPriorities::PRE_WRITE],
        //     KernelEvents::VIEW => ['onSuccessLoginAction', EventPriorities::PRE_VALIDATE],

        // ];

        return [
            RequestEvent::class => 'onSuccessLoginAction',
        ];
    }
    public function onSuccessLoginAction(RequestEvent $event)
    {
        $mixRouteParamsName = ($this->session->get('mixRouteParamsName', []));
        // dump($event);

        // if ( count($mixRouteParamsName) > 0) {
        //     // $urlRedirection = $this->router->generate('adds_new_cart', ['id' => $mixRouteParamsName['id']]);
        //     $urlRedirection = $this->router->generate('adds_list');

        //     $response = new RedirectResponse($urlRedirection);
        //     $event->setResponse($response);

        // }
       
        
    }

}