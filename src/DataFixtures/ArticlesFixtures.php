<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticlesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i= 1; $i<=10; $i++){
            $article = new Article;
            $article->setTitle("titre de l'article n° $i")
                 ->Setcontent("<p> Contenu de l'article n° $i </p>")
                 ->setImage("https://picsum.photos/id/237/200/300")
                 ->setCreatedAt(new\dateTime());


                 $manager->persist($article);

                //classe ojectManager est une classe predefinie en symfony qui permet de manipulerles ligne de la bdd(INSERT,UPDATE,DELETE)
                // persist() est une méthode issue de la classe ObjectManager qui permet de préparer les insertions et de les garder en mémoire.

        }


        $manager->flush();
    }
}
