<?php

namespace App\Services;

use Swift_Mailer;

class MailerService
{
    private $mailer;
    private $template;

    public function __construct(Swift_Mailer $mailer, \Twig\Environment $_template)
    {
        $this->mailer   = $mailer;
        $this->template = $_template;
    }
    
    public function sendMailToAdmin()
    {
        $tmp_template = "mail/adminmail/admin.html.twig";
        $template     = $this->template->render($tmp_template);

        $message = (new \Swift_Message('Hello Email'))
        ->setFrom('symfony9494@gmail.com')
        ->setTo('rakotoarisondan@gmail.com')
        ->setBody($template, 'text/html')
    ;

    $this->mailer->send($message);
    return true;

    }

}