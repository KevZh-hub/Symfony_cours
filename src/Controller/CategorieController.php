<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="categorie")
     */
    public function index(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager(); // Recup données

        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($categorie); // prépare la save
            $em->flush(); // execute la save

            $this->addFlash(
                'success',
                'Catégorie ajoutée'
            );
        }

        $categories = $em->getRepository(Categorie::class)->findAll();

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
            'ajout' => $form->createView()
        ]);
    }

/**
 * @Route("/categorie/{id}", name="show")
 */
    public function show(Categorie $categorie = null, Request $request) { //converti automatique l'id en une catégorie
        if($categorie == null) {
            $this->addFlash(
                'erreur',
                'La catégorie est introuvable'
            );
            return $this->redirectToRoute('categorie');
        }

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            $this->addFlash(
                'success',
                'Catégorie mis  à jour'
            );
        }

        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
            'maj' => $form->createView()
        ]);
    }

    /**
     * @Route("/categorie/delete/{id}", name="delete")
     */
    public function delete(Categorie $categorie = null) {
        if($categorie == null) {
            $this->addFlash(
                'erreur',
                'Catégorie introuvable'
            );
            return $this->redirectToRoute('categorie');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();

    $this->addFlash(
        'success',
        'Catégorie supprimée'
    );

    return $this->redirectToRoute('categorie');
    }
}