<?php

namespace App\Services;

use Swift_Mailer;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MailerService
{
    private $mailer;
    private $template;
    private $container;

    public function __construct(Swift_Mailer $mailer, \Twig\Environment $_template, ContainerInterface $container)
    {
        $this->mailer   = $mailer;
        $this->template = $_template;
        $this->container = $container;
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
        
        return true;

    }

}