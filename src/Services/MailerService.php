<?php

namespace App\Services;

use Swift_Mailer;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MailerService
{
    private $mailer;
    private $template;
    private $container;
    private $addService;

    public function __construct(Swift_Mailer $mailer, \Twig\Environment $_template, ContainerInterface $container, AddService $addService)
    {
        $this->mailer     = $mailer;
        $this->template   = $_template;
        $this->container  = $container;
        $this->addService = $addService;
    }
    
    public function sendMailToAdmin($paramsArticles, $purshaseDate)
    {
        $templateMailAdmin = $this->container->getParameter('template_mail_admin');
        
        $parametersMail = [
                            'cartArticles'  => $paramsArticles['cartArticles'],
                            'total'         => $paramsArticles['totalPrice'],
                            'purshaseDate'  => $purshaseDate
                            ];

        $template     = $this->template->render($templateMailAdmin, $parametersMail);
        $message      = (new \Swift_Message('Hello Email'))
                        ->setFrom('symfony9494@gmail.com')
                        ->setTo('rakotoarisondan@gmail.com')
                        ->setBody($template, 'text/html') ;

        $this->mailer->send($message);
        //suppression de toute les sessions liées aux achats après confirmation de l'achat
        $this->addService->removeSessionAction();


        return true;

    }

}