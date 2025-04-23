<?php

namespace App\Mail;

use App\Entity\Newsletter;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NewsletterSubscribedConfirmation
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function sendEmail(Newsletter $newsletter): void
    {
        // envoi d'un email de confirmation
        // Construction d'un email
        $email = (new Email())
        ->from('admin@hbft10.fr')
        ->to($newsletter->getEmail())
        ->subject('Bienvenue !')
        ->text('Votre email ' . $newsletter->getEmail() . ' a bien été enregistré dans notre liste de diffusion.')
        ->html('<p>Votre email ' . $newsletter->getEmail() . ' a bien été enregistré dans notre liste de diffusion.</p>');

        // Utilisation de la dépendance mailer pour envoyer l'email
        $this->mailer->send($email);
    }
}
