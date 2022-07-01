<?php

namespace App\Controller;

use DateTime;
use App\Entity\Vehicule;
use App\Form\VehiculeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminController extends AbstractController
{

//--------------------------AFFICHER LES VEHICULES ADMIN---------------------------------------------//
    #[Route('/adminvehicules', name: 'adminVehicules')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $vehicules = $doctrine->getRepository(Vehicule::class)->findAll();
        return $this->render('admin/adminVehicule.html.twig', [
            'vehicules' => $vehicules
        ]);
    }


//-------------------------------- CREATION D'UN VEHICULE -------------------------------------------------------------//

#[Route('/ajoutvehicule', name:'ajoutvehicule')]
public function ajoutadmin(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger)
{
    $vehicule = new Vehicule(); // On crée l'objet Vehicle
    $form = $this->createForm( VehiculeType::class, $vehicule);
    $form ->handleRequest($request); 

    if ($form ->isSubmitted() && $form->isValid()) 
    {
        
    // On récupere la photo depuis le formulaire
    $file= $form->get('imageForm')->getData();
    $fileName = $slugger->slug($vehicule->getTitre() ) . uniqid() . '.' . $file->guessExtension();
    $file->guessExtension();

    try{
    $file->move($this->getParameter('photos_vehicules'), $fileName);
    }catch(FileExeption $e)
    {
    // gérer les exeptions
    }
    $vehicule->setPhoto($fileName);
        $vehicule->setDateEnregistrement(new DateTime("now"));
        $manager = $doctrine->getManager();
        $manager -> persist($vehicule);
        $manager->flush();
        $this->addFlash('success', "Ajout d'un vehicule validé !");
        return $this->redirectToRoute("formVehicule"); 
        }
        return $this->render("form-vehicule.html.twig", [
            'formVehicule' => $form->createView()
        ]);
}


//------------------------------- UPDATE MISE A JOUR----------------------------------------------------//

    #[Route('adminUpdateVehicule/{id<\d+>}', name: 'adminUpdateVehicule')]
    public function update(ManagerRegistry $doctrine , $id,SluggerInterface $slugger, Request $request): Response
    {
        $vehicule = $doctrine->getRepository(Vehicule::class)->find($id);
        // dd($vehicule);
        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);

        // On stocke la photo du vehicule à mettre à jour 
        $image = $vehicule->getPhoto();

        if( $form->isSubmitted() && $form->isValid() )
        {
            
           if($form->get('imageForm')->getData())
            {
                $photoFile = $form->get('photoFile')->getData();
                $fileName = $slugger->slug($vehicule->getTitre()) . uniqid() . '.' . $photoFile->guessExtension();

                try{
                    $photoFile->move($this->getParameter('photos_vehicules'), $fileName);
                }catch(FileException $e)
                {
                    // exeptions
                }
                $vehicule->setPhoto($fileName);

            }
                $manager = $doctrine->getManager();
                $manager->persist($vehicule);
                $manager->flush();

                $this->addFlash('success', "Mise à jour effectué!");
                return $this->redirectToRoute('adminVehicules');

            //  // On récupère l'image du formulaire
            //  $photoFile = $form->get("photoForm")->getData();
            //  // On crée un nouveau nom pour l'image
            //  $fileName = $slugger->slug($vehicule->getTitre()) . uniqid() . '.' . $photoFile->guessExtension();
            //  // On déplace l'image dans le dossier parametré dans service.yaml
 
            //  try{
            //      $photoFile->move($this->getParameter('photo_vehicules'), $fileName);
            //  } catch(FileException $e){
            //      // gestion des erreurs upload
            //  }

            //  $vehicule->setPhoto($fileName);
            //  $manager=$doctrine->getManager();
            //  $manager->persist($vehicule);
            //  $manager->flush();
 
            //  return $this->redirectToRoute('adminVehicule');

            // }
        }

        return $this->render('form-vehicule.html.twig', [
            'formVehicule' => $form->createView()
        ]);
    }

//--------------------------------------- SUPPRIMER VEHICULE -------------------------------------------------------------//

#[Route('/adminDeleteVehicule/{id}', name:'adminDeleteVehicule')]
public function delete(ManagerRegistry $doctrine, $id)  
{
 // on récupère l'article dont l'id est celui passé en paramètre de la fonction
 $vehicule = $doctrine->getRepository(Vehicule::class)->find($id);
 //On réculère le manager de doctrine
 $manager = $doctrine->getManager();
 // On prépare la suppression de l'article
 $manager->remove($vehicule);
 // On exucute l'action (suppression)
 $manager->flush();
 $this->addFlash('success', "Suppression validé !");
 return $this->redirectToRoute("adminVehicules");
}



}
