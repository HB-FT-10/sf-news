<?php

namespace App\Controller;

use App\Entity\ContactRequest;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function home(): Response
    {
        return $this->render('index/home.html.twig');
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        $authorsNames = [
            "Barry Francis", "Hannah Ballard", "Ralph Waters", "Barbara Figueroa"
        ];

        return $this->render('index/about.html.twig', [
            'authors' => $authorsNames
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, EntityManagerInterface $em): Response
    {
        $contactRequest = new ContactRequest();
        $form = $this->createForm(ContactType::class, $contactRequest);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($contactRequest);
            $em->flush();

            $this->addFlash('success', "Merci, votre demande a bien été enregistrée");

            return $this->redirectToRoute('homepage');
        }

        return $this->render('index/contact.html.twig', [
            'contactForm' => $form
        ]);
    }
}
