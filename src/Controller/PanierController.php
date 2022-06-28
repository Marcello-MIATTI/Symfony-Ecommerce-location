<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function affichePanier(): Response
    {
        $panier = ['produit1'=> ['id' =>'1', 'titre' =>'pomme', 'description' =>'bio', 'prix'=>20],
     'produit2'=> ['id' =>'2', 'titre' =>'banane', 'description' =>'bio', 'prix'=>20],
     'produit3'=> ['id' =>'3', 'titre' =>'fraise', 'description' =>'bio', 'prix'=>20],
     'produit4'=> ['id' =>'4', 'titre' =>'poire', 'description' =>'bio', 'prix'=>20]];

        return $this->render('panier.html.twig', [
            'panier' => $panier
        ]);
    }
}
