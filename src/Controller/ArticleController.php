<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article")
     */
    public function index(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager(); // Recup données

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($article); // prépare la save
            $em->flush(); // execute la save

            $this->addFlash(
                'success',
                'Catégorie ajoutée'
            );
        }

        $articles = $em->getRepository(Article::class)->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'ajout' => $form->createView()
        ]);
    }

/**
 * @Route("/article/{id}", name="showArticle")
 */
    public function show(Article $article = null, Request $request) { //converti automatique l'id en un article
        if($article == null) {
            $this->addFlash(
                'erreur',
                'L article est introuvable'
            );
            return $this->redirectToRoute('article');
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            $this->addFlash(
                'success',
                'Article mis à jour'
            );
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'maj' => $form->createView()
        ]);
    }

    /**
     * @Route("/article/delete/{id}", name="deleteArticle")
     */
    public function delete(Article $article = null) {
        if($article == null) {
            $this->addFlash(
                'erreur',
                'Article introuvable'
            );
            return $this->redirectToRoute('article');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();

    $this->addFlash(
        'success',
        'Article supprimée'
    );

    return $this->redirectToRoute('article');
    }
}