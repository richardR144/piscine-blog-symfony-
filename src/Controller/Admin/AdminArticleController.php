<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminArticleController extends AbstractController
{
//tu as créé la route nommée "admin_list_articles" dont l'url est /admin/articlesy
    #[Route('/admin/articles', 'admin_list_articles')]
    //je créé ma fonction adminListArticles qui retourne un template twig contenant les articles
    public function adminListArticles(ArticleRepository $articleRepository): Response
    {

        $articles = $articleRepository->findAll();

        return $this->render('admin/page/article/list_articles.html.twig', [
           'articles' => $articles
        ]);
    }
// là tu as créé un fonctoin pour effacer des articles
    #[Route('/admin/articles/delete/{id}', 'admin_delete_article')]
    public function deleteArticle(int $id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager)
    {
        // là tu lui demandes de tous les trouver (les articles) par leurs identifiants
        // mais juste un article par son id
        $article = $articleRepository->find($id);
        //sinon tu lui envoies un message d'erreurs
        if (!$article) {
            $html404 = $this->renderView('admin/page/404.html.twig');
            return new Response($html404, 404);
        }

        try {
            $entityManager->remove($article);
            $entityManager->flush();

            // permet d'enregistrer un message dans la session de PHP
            // ce message sera affiché grâce à twig sur la prochaine page
            $this->addFlash('success', 'Article bien supprimé !');
// il va essayer de faire le code sinon il envoi un message d'erreur (Try /catch, essayer soit page d'erreur
        } catch(\Exception $exception){
            return $this->renderView('admin/page/error.html.twig', [
                'errorMessage' => $exception->getMessage()
            ]);
        }

        return $this->redirectToRoute('admin_list_articles');
    }

// là tu insères des articles, le reste je ne sais plus!!
    #[Route('/admin/articles/insert', 'admin_insert_article')]
    public function insertArticle(Request $request, EntityManagerInterface $entityManager)
    {
        $article = new Article();
//je ne sais plus
        $articleCreateForm = $this->createForm(ArticleType::class, $article);

        $articleCreateForm->handleRequest($request);

        if($articleCreateForm->isSubmitted() && $articleCreateForm->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'article enregistré');

            return $this->redirectToRoute('admin_list_articles');
        }
//là c'est pour créer une view
        $articleCreateFormView = $articleCreateForm->createView();

        return $this->render('admin/page/article/insert_article.html.twig', [
            'articleForm' => $articleCreateFormView
        ]);

    }
// là tu mets à jour les articles
    #[Route('/admin/articles/update/{id}', 'admin_update_article')]
    public function updateArticle(int $id, Request $request, EntityManagerInterface $entityManager, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);

        $articleCreateForm = $this->createForm(ArticleType::class, $article);

        $articleCreateForm->handleRequest($request);
        //dans la méthode on créé une instance de la classe Article,
        // puis on demande à Symfony de gérer la récupération des données
        // du formulaire grâce à la classe de gabarit de formulaire (ArticleType).
        if ($articleCreateForm->isSubmitted() && $articleCreateForm->isValid()) {
            //La méthode persist() est utilisée pour indiquer à l’entity
            // manager que l’objet passé en paramètre doit être persisté
            $entityManager->persist($article);
            // flush est pour éxécuter une requête
            $entityManager->flush();
            $this->addFlash('success', 'article enregistré');
        }

        $articleCreateFormView = $articleCreateForm->createView();

        return $this->render('admin/page/article/update_article.html.twig', [
            'articleForm' => $articleCreateFormView
        ]);

    }

}