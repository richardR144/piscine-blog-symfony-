<?php

declare(strict_types=1);

namespace App\Controller\Public;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

    #[Route('/articles', 'list_articles')]
    public function listArticles(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->render('public/page/article/list_articles.html.twig', [
           'articles' => $articles
        ]);
    }


    #[Route('/articles/{id}', 'show_article')]
    public function showArticle(int $id, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->find($id);

        if (!$article || !$article->getIsPublished()) {
            $html404 = $this->renderView('public/page/404.html.twig');
            return new Response($html404, 404);
        }

        return $this->render('public/page/article/show_article.html.twig', [
            'article' => $article
        ]);
    }

}