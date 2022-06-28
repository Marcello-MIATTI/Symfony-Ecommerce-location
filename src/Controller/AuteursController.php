<?php

namespace App\Controller;

use App\Entity\Auteurs;
use App\Form\AuteursType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuteursController extends AbstractController
{
//--------------------------------- AFFICHER LES AUTEURS----------------------------------------------------//
#[Route('/auteurs', name: 'auteurs')]
public function index(ManagerRegistry $doctrine): Response
{

    $auteurs = $doctrine->getRepository(Auteurs::class) ->findAll();
    //dd($auteurs);
    return $this->render('auteurs/auteurs.html.twig', [
        'auteurs' => $auteurs
    ]);
}

//------------------------------- AJOUTER UN AUTEUR------------------------------------------------------------//
    #[Route('/ajout-auteurs', name: 'ajout-auteurs')]
    public function ajouterAuteur(ManagerRegistry $doctrine, Request $request): Response
    {
    
    $auteur = new Auteurs();
    $form = $this->createForm(AuteursType::class, $auteur);
    $form ->handleRequest($request); 
    if ($form ->isSubmitted() && $form->isValid()) 
    // vérif ddu clic btn et si les infos sont correctes
    {
        // On récupère le manager de doctrine
        $manager = $doctrine->getManager();
        // On persiste l'objet
        $manager -> persist($auteur);
        // puis on envoie dans la bdd
        $manager->flush();
        
        return $this->redirectToRoute("auteurs"); 
        // reidrection , on utilise "name" dans la route
    }
        return $this->render("auteurs/form-Auteur.html.twig", [
        'formAuteurs' => $form->createView()
        ]);

        return $this->render('auteurs/index.html.twig', [
            'auteur' => $auteur
        ]);
    }

    //--------------------------------------- MISE A JOUR AUTEURS -------------------------------------------------------------//

    // On crée la route de pour la modification en fonction de l'id de l'article
    #[Route('/update_auteur/{id}', name:'update_auteur')]
    // On crée la méthode pour le traitement de la mise à jour
    public function update(ManagerRegistry $doctrine, $id, Request $request)  // $id aura comme valeur l'id passé en paramètre de la route
    {
        // on récupère l'article dont l'id est celui passé en paramètre de la fonction
        $auteur = $doctrine->getRepository(Auteurs::class) ->find($id);

        $form = $this->createForm( AuteursType::class, $auteur);
        // On crée le formulaire en le liant le formType à l'objet crée

        $form ->handleRequest($request); 
        // on donne accès aux données du formulaire pour la validation

    if ($form ->isSubmitted() && $form->isValid()) 
    // vérif ddu clic btn et si les infos sont correctes
    {
        // On récupère le manager de doctrine
        $manager = $doctrine->getManager();
        // On persiste l'objet
        $manager -> persist($auteur);
        // puis on envoie dans la bdd
        $manager->flush();

        return $this->redirectToRoute("auteurs"); 
        // reidrection , on utilise "name" dans la route
    }
        return $this->render("auteurs/form-Auteur.html.twig", [
            'formAuteurs' => $form->createView()
        ]);
    }

    //--------------------------------------- SUPPRIMER AUTEURS-------------------------------------------------------------//
    #[Route('/delete_auteur{id}', name:'delete_auteur')]
    public function delete(ManagerRegistry $doctrine, $id)  // $id aura comme valeur l'id passé en paramètre de la route
    {
        // on récupère l'article dont l'id est celui passé en paramètre de la fonction
        $auteur = $doctrine->getRepository(Auteurs::class)->find($id);
        //On réculère le manager de doctrine
        $manager = $doctrine->getManager();
        // On prépare la suppression de l'article
        $manager->remove($auteur);
        // On exucute l'action (suppression)
        $manager->flush();
        return $this->redirectToRoute("auteurs");
    }


//---------------------------------- AFFICHER UN AUTEUR -----------------------------------------------------------------//

    // On crée la route de pour la modification en fonction de l'id de l'article
    #[Route('/unAuteur{id}', name:'unAuteur')]
    // On crée la méthode pour le traitement de la mise à jour
    public function unArticle(ManagerRegistry $doctrine, $id, Request $request)   // $id aura comme valeur l'id passé en paramètre de la route
    {
    // on récupère l'article dont l'id est celui passé en paramètre de la fonction
        $auteur = $doctrine->getRepository(Auteurs::class) ->find($id);
        return $this->render("auteurs/unAuteur.html.twig", [
        'auteur' => $auteur
        ]);
    }








}