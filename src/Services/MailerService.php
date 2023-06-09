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
    
    public function sendMailToCustomer($paramsArticles, $currentCustomer, $purshaseDate)
    {
        $templateMailAdmin = $this->container->getParameter('template_mail_customer');
        
        $parametersMail    = [
                                'cartArticles'  => $paramsArticles['cartArticles'],
                                'total'         => $paramsArticles['totalPrice'],
                                'purshaseDate'  => $purshaseDate,
                            ];

        $template     = $this->template->render($templateMailAdmin, $parametersMail);
        $message      = (new \Swift_Message('Hello Email'))
                        ->setFrom('symfony9494@gmail.com')
                        ->setTo($currentCustomer->getEmail())
                        ->setBody($template, 'text/html') ;

        $this->mailer->send($message);
        //suppression de toute les sessions liées aux achats après confirmation de l'achat
        // $this->addService->removeSessionAction();


        return true;

    }

    public function sendMailToAdmin($paramsArticles, $currentCustomer, $purshaseDate)
    {
        $templateMailAdmin = $this->container->getParameter('template_mail_admin');
        
        $parametersMail    = [
                                'cartArticles'  => $paramsArticles['cartArticles'],
                                'total'         => $paramsArticles['totalPrice'],
                                'curentCustomer'=> $currentCustomer->getUserName(),
                                'mailCustomer'  => $currentCustomer->getEmail(),
                                'purshaseDate'  => $purshaseDate,
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



    public function sendMailToNewRegistredCustomer($emailCustomer, $email, $userRegistredPassword = null, $activation = 0)
    {



        $validation_path = ($this->container->getParameter('validation_path'));
        $templateNewRegisterCustomer = "mail/customermail/confirm-registration-customer.html.twig";
        $parametersMail = [
                            'newCustomerEmail' => $email,
                            'urlBaseSmart' => $validation_path,
                            'userRegistredPassword' => $userRegistredPassword,
                            'activation' => $activation
                        ];
                        try {
                            //code...
                            $template     = $this->template->render($templateNewRegisterCustomer, $parametersMail);
                        } catch (\Throwable $th) {
                            dd($th->getMessage());
                        }
        $message      = (new \Swift_Message('Confirmation de votre compte smart'))
                        ->setFrom('symfony9494@gmail.com')
                        ->setTo('rakotoarisondan@gmail.com')
                        ->setBody($template, 'text/html') ;

        $this->mailer->send($message);

        return true;
    }


}