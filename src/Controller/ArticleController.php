<?php

namespace App\Controller;

use DateTime;
use App\Entity\Articles;
use App\Form\ArticleType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ArticleController extends AbstractController
{

//--------------------------------- AFFICHER TOUT LES ARTICLES------------------------------------------------------------------//
    #[Route('/articles', name: 'articles')]  // Route de la page de tous les articles
    public function index(ManagerRegistry $doctrine): Response
    {
        $Articles = $doctrine->getRepository(Articles::class) ->findAll();
        //dd($Articles);
        return $this->render('article/allArticles.html.twig', [
            'Articles' => $Articles
        ]);
    }
  //-------------------------------- CREATION D'UN ARTICLE -------------------------------------------------------------//

    #[Route('/ajout-article', name:'ajout-article')]
    public function ajout(ManagerRegistry $doctrine, Request $request)
    {
        $acticle = new Articles(); // On crée l'objet article
        $form = $this->createForm( ArticleType::class, $acticle);
        // On crée le formulaire en le liant le formType à l'objet crée

        $form ->handleRequest($request); 
        // on donne accès aux données du formulaire pour la validation

    if ($form ->isSubmitted() && $form->isValid()) 
    // vérification du clic de validation et si les infos sont saisi sont conforme
        {
        // Je m'occupe d'affecter les données manquantes (qui ne parviennent pas du formulaire)
        $acticle->setDateDeCreation(new DateTime("now"));
        // On récupère le manager de doctrine
        $manager = $doctrine->getManager();
        // On persiste l'objet
        $manager -> persist($acticle);
        // puis on envoie dans la bdd
        $manager->flush();

        return $this->redirectToRoute("app_article"); 
            // reidrection , on utilise "name" dans la route
        }
        return $this->render("article/formulaire.html.twig", [
            'formArticle' => $form->createView()
        ]);
    }

    //--------------------------------------- MISE A JOUR ARTICLE -------------------------------------------------------------//

    // On crée la route de pour la modification en fonction de l'id de l'article
    #[Route('/update_article/{id}', name:'update_article')]
    // On crée la méthode pour le traitement de la mise à jour
    public function update(ManagerRegistry $doctrine, $id, Request $request)  // $id aura comme valeur l'id passé en paramètre de la route
    {
        // on récupère l'article dont l'id est celui passé en paramètre de la fonction
        $acticle = $doctrine->getRepository(Articles::class) ->find($id);

        $form = $this->createForm( ArticleType::class, $acticle);
        // On crée le formulaire en le liant le formType à l'objet crée

        $form ->handleRequest($request); 
        // on donne accès aux données du formulaire pour la validation

    if ($form ->isSubmitted() && $form->isValid()) 
    // vérif ddu clic btn et si les infos sont correctes
    {
        // Je m'occupe d'affecter les données manquantes (qui ne parviennent pas du formulaire)
        $acticle->setDateDeModification(new DateTime("now"));
        // On récupère le manager de doctrine
        $manager = $doctrine->getManager();
        // On persiste l'objet
        $manager -> persist($acticle);
        // puis on envoie dans la bdd
        $manager->flush();

        return $this->redirectToRoute("app_article"); 
        // reidrection , on utilise "name" dans la route
    }
        return $this->render("article/formulaire.html.twig", [
            'formArticle' => $form->createView()
        ]);
    }

    //--------------------------------------- SUPPRIMER ARTICLE -------------------------------------------------------------//

    #[Route('/delete_article/{id}', name:'delete_article')]
    public function delete(ManagerRegistry $doctrine, $id)  
    {
        // on récupère l'article dont l'id est celui passé en paramètre de la fonction
        $article = $doctrine->getRepository(Articles::class)->find($id);
        //On réculère le manager de doctrine
        $manager = $doctrine->getManager();
        // On prépare la suppression de l'article
        $manager->remove($article);
        // On exucute l'action (suppression)
        $manager->flush();
        return $this->redirectToRoute("app_article");
    }

    //---------------------------------- AFFICHER UN ARTICLE -----------------------------------------------------------------//

    // On crée la route de pour la modification en fonction de l'id de l'article
    #[Route('/unArticle/{id}', name:'unArticle')]
    // On crée la méthode pour le traitement de la mise à jour
    public function unArticle(ManagerRegistry $doctrine, $id, Request $request)   // $id aura comme valeur l'id passé en paramètre de la route
    {
    // on récupère l'article dont l'id est celui passé en paramètre de la fonction
        $article = $doctrine->getRepository(Articles::class) ->find($id);
        return $this->render("article/unArticle.html.twig", [
        'unArticle' => $article
        ]);
    }

}
