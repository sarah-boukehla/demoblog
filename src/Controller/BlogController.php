<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function index(ArticleRepository $repo)
    {
        /*Un des principes de principe de base de Symfony est l'injection de dépendances.
                Par exemple, ici dans le cas de la méthode index(), cette a besoin de la classe ArticleRepository pour fonctionner correctement, c'est à dire que la méthode index() dépend de la classe ArticleRepository
                Donc ici on injecte une dépendance en argument de la méthode index(), on impose un objet issu de la classe ArticleRepository
                Du coup plus besoin de faire appel à Doctrine (getDoctrine())
                $repo est un objet issu de la classe ArticleRepository et nous avons accès à toute les méthodes issues de cette classe
                Les méthodes sont moins chargé et c'est plus simple d'utilisation.

        
        /**
         * pour selectionner des donneés en bdd nous avons besoin de la classe et de la class article
         * une classe repository permet uniquement de selectionner des données en BDD requete SQL select
         * on a besoin de l'ORM doctrine pour faire la relation entre le controller et la BDD( getdoctrine)
         * get repostiry : methode issu de l'objet doctrine qui permet d'importer une classe requistory
         * $repo et un objet issue de la class article repository cette methode est predefinies par symfonypermettant de selectionner des donnes en bdd 
         * (find)
         * findAll est une methode issue de la classe l article repository permettant de selectionner la table sql doc ici la table article
         */
    
        $repo= $this-> getDoctrine()->getRepository(Article::class);

        $article= $repo ->findAll();
        dump($article);

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'article'=>$article
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
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */           //12
    public function form(Article $article = null,Request $request, EntityManagerInterface $manager) 
    {
        /*
        La classe request est prefedefinies en symfony qui stock toutes les donnes par la superglobales ($_POST),($_Server)
        on a acces aux donnes saisi dans le formulaire via l'ojet request 
        la propriete $request-> represente la superglobale $_POST les donnees saisi  dans le formaulaire sont accesible via cette propriete
        pour inserer un nouvelle article entite articlepour avoir un objet article vide afin de renseigner tous les setteurs de lobjet $article
        entity manager interface interface predefinies de symfony qui permet de modier les lignes de la bdd elle a de methodes qui permettent d'executer les requete sql persist et flush
        persist permet de preparer et de sticker
        flush liberer et d'execueter
        RedirecttoRoute methode predefini redirige apres insertion vers route(blog_show ou autre)detail l'article que lon viens d'insereret on transmet la methode de l'id de l'article aenvoyer dans l'url
        get: methodede l'objet $request qui permet de recuperer les donnes saisaux differnts indice "name"du formulaire
        */
        dump($request);

 //       if($request->request->count() > 0){
//         $article =new Article;

//  /           $article->setTitle($request->get('title'))
//                     ->setContent($request->request ->get('content'))
//                     ->setImage($request->request->get('image'))
//                     ->setCreatedAt(new \datetime());

//                     $manager->persist($article);
//                     $manager->flush();
//                     dump($article);
//                     return $this->redirectToRoute('blog_show', [
//                         'id'=> $article-getId()

//                     ]
//        
//Si l'article n'est pas existant,n'est pas defini est quil est nul,cela veut dire quil nya aucun ID n'a ete transmis dans l'URL donc c'est une insertion ,alors on instancie la class article afin d'avoirun objet $article vide
//on entre dans la condition seulement dans le cas d'une insertion d'un nouvelle article.
        if(!$article)
        {
            $article = new article;
        }


// on importe la classe qui permet de creer le formulaire d'ajout/modification article
//(articletype).on envoi un 2eme argumentobjet article pour bien specifierque lque le formaulaire est destinee  a remplir l'objet de l'article
        $form = $this->createForm(ArticleType::class,$article);
       
        if(!$article->getId())

        {
        $article->setCreatedAt(new \datetime);
        }

        // $form = $this->createFormBuilder($article)             
        // ->add('title')
        // ->add('content')
        // ->add('image')
        // ->getForm();
        $form->handleRequest($request);
//methode handlerequest permet de recuperer toute les valeurs 
        if($form->isSubmitted()&& $form->isValid()){
            $article->setCreatedAt(new \dateTime);
            $manager->persist($article);
            $manager->flush();
            dump($article);
            return $this->redirectToRoute('blog_show',[
                'id'=>$article->getId()
            ]);
            
            

        }
                
        return $this->render('blog/create.html.twig', [
            'formArticle'=> $form->createView(),
            'editMode' => $article->getId() !==null
        ]);
       
    }



    /**
     * @route("/blog/{id}", name="blog_show")
     * 
     */

    public function show($id)// Id1
    {
         /*
    pour selectionner 1 article en BDD, c'est aà dire voir le detail d'1 article nous utilisons le principe de route parametrée ("/blog/{id}"), notre route attends un parametre de type {id} donc l'id est stockée en BDD.
    lorsque nous transmettons dans l'url une route par exemple"/blog/9" on envoi un id dans lurl symfony va automatiquement recuperer ce parametre pour le transmettre en argument de la methode show id
    nous avons acces a lid a l'interieur de la methode show
    le but est de selectionner les donnees en BDDde l'ID recuperer en parametre
        Le but est de selectionner les données en BDD de l'{id} récupéré en paramètre
            Nous avons besoin pour cela de la classe ArticleRepository afin de pouvoir selectionner en BDD
            La méthode find() est issue de la classe ArticleRepository et permet de selectionner des données en BDD avec un argument de type {id}
            DOCTRINE fait ensuite tout le travail pour nous, c'est à dire qu'elle recupère la requete de selection pour l'ex

    */

        $repo =$this ->getDoctrine()->getRepository(Article::class);
        $article= $repo->find($id); //on envoie le template

        dump($article);
        return $this->render('blog/show.html.twig', [
            'article'=>$article
        ]);
        //arguments render ('template_a_envoyer', ARRAY option')

    }

    
}

