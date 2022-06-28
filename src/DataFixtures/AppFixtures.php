<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Articles;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($i=1 ; $i<= 10 ; $i++){
            $article = new Articles();
            $article-> setTitre("mon titre $i")
                    -> setContenu("mon contenu $i")
                    -> setDateDeCreation(new DateTime("now"));
            $manager->persist($article);
        }

        $manager->flush();
    }
}
