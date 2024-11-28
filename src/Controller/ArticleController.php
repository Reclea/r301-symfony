<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);   
    }


    #[Route('article/creer', name: 'app_article_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_liste');
        }

        
        // $article->setTitre('Article ')
        // ->setTexte('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat sit amet purus in volutpat. Fusce ipsum nulla, venenatis vitae faucibus a, ultrices vel dolo')
        // ->setPublie(1)
        // ->setDate(new \DateTimeImmutable());

        // //dd($article);
       // return $this->render('article/creer.html.twig',[
       //     'controller_name' => 'ArticleController',
       //     'titre' => 'Article',
       //     'article' => $article
       // ]);


        return $this->render('article/creer.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    
    #[Route('/article/liste', name: 'app_article_liste')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->getRepository(Article::class)->findAll();

        return $this->render('article/liste.html.twig', [
            'articles' => $articles
        ]);
    }


    #[Route('/article/update/{id}', name: 'app_article_update')]
    public function update(EntityManagerInterface $entityManager, int $id): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$id) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $article->setTitre('New product name!');
        $entityManager->flush();

        return $this->redirectToRoute('app_article_liste', [
            'id' => $article->getId()
        ]);
    }

    #[Route('/article/delete/{id}', name: 'app_article_delete')]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$id) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $entityManager->remove($article);
        $entityManager->flush();


        return $this->redirectToRoute('app_article_liste', [
            'id' => $article->getId()
        ]);
    }
}