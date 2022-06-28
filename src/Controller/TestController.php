<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index()
    {
        $prenom = "Marcello";
        $nom = "MIATTI";

        $identite = [ 'personne1' => 
        ["prenom" => 'Mickaël',
        "nom" => 'MIATTI',
        "nationnalité" => 'française'],

        'personne2' => 
        ["prenom" => 'Marcello',
        "nom" => 'MIATTI',
        "nationnalité" => 'française']];
        
        return $this->render("test.html.twig" , [
            'prenom' => $prenom,
            'nom' => $nom,
            'identite' => $identite
        ]
    );
    }
}
