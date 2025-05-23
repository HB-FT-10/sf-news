<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Form\NewsletterType;
use App\Mail\NewsletterSubscribedConfirmation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class NewsletterController extends AbstractController
{
    #[Route('/newsletter/subscribe', name: 'app_newsletter_subscribe')]
    public function subscribe(
        Request $request,
        EntityManagerInterface $em,
        NewsletterSubscribedConfirmation $confirmationService,
        HttpClientInterface $spamChecker
    ): Response {
        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Spam ?
            $response = $spamChecker->request(Request::METHOD_POST, "/api/check", [
                'json' => ['email' => $newsletter->getEmail()]
            ]);

            $data = $response->toArray();

            if ($data['result'] === 'spam') {
                $form->addError(new FormError("L'email renseigné est un spam"));
            }

            if ($data['result'] === 'ok') {
                // enregistrer mon email
                // persist + flush
                $em->persist($newsletter);
                $em->flush();

                $confirmationService->sendEmail($newsletter);

                // Ajout d'un message de succès (notification)
                $this->addFlash('success', 'Votre inscription a bien été prise en compte, un email de confirmation vous a été envoyé');
            }
        }

        return $this->render('newsletter/subscribe.html.twig', [
            'newsletterForm' => $form
        ]);
    }
}
