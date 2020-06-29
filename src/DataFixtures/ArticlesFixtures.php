<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticlesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        //creation de 3 categories
        for($i = 1; $i <= 3; $i++){

            $category = new Category;
            $category->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph());

            $manager->persist($category); 
            // creation entre 4 et 6 articles dans categorie
            for($j =1; $j<=mt_rand(4,6); $j++)
            {    
                $article= new Article;

                $content ='<p>' . join($faker->paragraphs(5),'</p><p>').'</p>';


                $article->setTitle($faker->sentence())
                        ->setContent($content)
                        ->setImage($faker->imageUrl())
                        ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                        ->setCategory($category);

                $manager->persist($article);

                //creation entre 4 et 10 commentaires par article
                for($k=1;$k<=mt_rand(4,10); $k++){

                    $comment = new Comment;

                    $content='<p>' . join($faker->paragraphs(2), '</p><p>'). '</p>';
                    $now = new \DateTime;
                    $interval=$now->diff($article->getCreatedAt());//represente le temps de timestand entre la date est la creation  de l'article et maintenant
                    $days= $interval->days;//nombre de jour entre la date est la creation de l'articleet maintenant
                    $minimum ='-'. $days .'days';
                
                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween($minimum))
                            ->setArticle($article);

                    $manager->persist($comment);
                }
            }
      
        }

        $manager->flush();
    }
}
