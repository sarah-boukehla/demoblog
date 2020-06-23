<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /* symfony fonctionne toujours avec un systeme de routage.une methode  d'un controlleur sera executee en fonction de la route transmise dans url.
    ex:si nous envoyons la route '/blog dans l'url(https://localhost:8000/blog, cela fait appel au controller'blogcontroller')
    symfony se sert des annotations (@route())
    Les annotation doivent toujours contenir 4 asterix
    */

    /**
     * @Route("/blog", name="blog")
     */
    public function index()
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    /**
     * @route("/",name="home")
     */
    public function home()
    {
        return $this ->render ('blog/home.html.twig',[

            'title'=> 'bienvenue sur le blog symfony',
        
        'age'=>25

        ] );
    }

    /**
     * @route("/blog/12", name="blog_show")
     */
    public function show(){

        return $this->render('blog/show.html.twig');
    }
}

