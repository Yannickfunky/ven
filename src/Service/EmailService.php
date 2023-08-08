<?php

// src/Service/EmailService.php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail($recipientEmail, $subject, $content)
    {
        $email = (new Email())
            ->from('yc.swim@gmail.com')
            ->to($recipientEmail)
            ->subject($subject)
            ->text($content);

        $this->mailer->send($email);
    }
}

?>