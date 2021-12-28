<?php

namespace App\Event\Listener;

use Swift_Mailer;
use App\Event\TechnoBuyEvent;
use App\Services\MailerService;

class TechnoEventListener
{
    private $mailerService;

    public function __construct(MailerService  $mailer) {
        $this->mailerService = $mailer;
    }
    /**
     * Undocumented function
     *
     * @param TechnoBuyEvent $techEvent
     * @return void
     */
    public function onSendMailAction(TechnoBuyEvent $techEvent)
    {

        $this->mailerService->sendMailToAdmin();
    //     $this->mailerService->sendMailConfirmToUser();
    //     // $message = (new \Swift_Message('Hello Email'))
    //     $message = \Swift_Message::newInstance()
    //     ->setFrom('symfony9494@gmail.com')
    //     ->setTo('rakotoarisondan@gmail.com')
    //     ->setBody("Envoi 12 Mail by Dan", 'text/html')
    // ;
return true;


    // $this->mailer->send($message);
    // $output->writeln('Successful you send a self email');
    }

}