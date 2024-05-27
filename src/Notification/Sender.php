<?php

namespace App\Notification;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class Sender
{

    public function __construct(private readonly MailerInterface $mailer, private readonly Environment $twig)
    {
    }

    public function sendNewUserNotificationToAdmins(User $user): void
    {

        $message = new Email();
        $content =  $this->twig->render('email/created_email.html.twig', [
            'user' => $user
        ]);
        $message->from('compte@enic.fr')
            ->to('admin@eni.fr')
            ->subject('New account has been created')
            ->html($content);
        $this->mailer->send($message);
    }
}