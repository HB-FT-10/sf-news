<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function contact(): Response
    {
        $name = "Guillaume";

        return $this->render('index/contact.html.twig', [
            'name' => $name
        ]);
    }
}
