<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Security\Voter\ArticleVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

final class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'articles_list')]
    public function list(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAllWithCategories();

        return $this->render('articles/list.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route('/articles/{id}', name: 'articles_item')]
    public function item(Article $article): Response
    {
        return $this->render('articles/item.html.twig', ['article' => $article]);
    }

    #[Route('/articles/edit/{id}', name: 'article_edit')]
    public function edit(Article $article): Response
    {
        $this->denyAccessUnlessGranted(ArticleVoter::EDIT, $article);

        return $this->render('articles/edit.html.twig', ['article' => $article]);
    }

    #[Route('/api/articles', name: 'api_articles_list')]
    public function apiArticles(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy([], limit: 10, offset: 0);

        return $this->json($articles, context: [
            'groups' => ['articles:read'],
            DateTimeNormalizer::FORMAT_KEY => 'd/m/Y'
        ]);
    }
}
