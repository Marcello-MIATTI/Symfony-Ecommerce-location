<?php

namespace App\Controller;

use App\Entity\Articles;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index(ManagerRegistry $doctrine): Response
    {
        // On recherche le dernier article inséré dans la base de donnée en utilisant le repository
        // de la class Article (ArticleRepository)
        $dernierArticle = $doctrine->getRepository(Articles::class) ->findOneBy([],

        ["dateDeCreation"=>"DESC"]);
        // dd() > dump and die
        // dd($dernierArticle); 
        return $this->render('home/index.html.twig',[
            'dernierArticle' => $dernierArticle
        ]);
    }
}
