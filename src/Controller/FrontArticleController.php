<?php


namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;

class FrontArticleController extends AbstractController


// Pour l'utilisateur qui visualise les pages du navigateur en front, il a besoin de voir afficher:

// la page qui affiche tous les articles avec les titres et images,

// et, la page qui affiche 1 article, avec titre, image et contenu.



{

    /**
     * Je créee une route pour faire afficher la liste de tous les articles sur une page du navigateur de mon front.
     *(Pour ne pas que j'oublie : On utilise une annotation tout comme ORM)
     *
     * @Route("/front/article/list", name="front_article_list")
     */


    // Puis je récupère tous mes articles dans ma BBD
    // en utilisant la méthode publique "articleListe" et le mécanisme d'autowire de SF
    // ayant pour paramètres la classe Articlerepository et la variable $articleRepository.
    // Grâce à la méthode "findAll" de la classe ArticleRepository,
    // J'envoie une requête SELECT ALL à ma BDD pour récupérer tous les articles de ma table.


    public function articleList(ArticleRepository $articleRepository)
    {

        $articles = $articleRepository->findAll();

        // Je retourne grâce à la méthode render de la classe Abstracontroller dont j'ai fait hériter
        // la class AdminArticleController.
        // je mets en paramètres de la méthode render, le nom du fichier article.html.twig et d'un tableau
        // ayant pour variable 'articles' le nom de ma variable dans twig et $articles sa valeur dans ma BDD

        return $this->render('front/article.html.twig', [
            'articles' => $articles
        ]);

    }

    // Je crée une nouvelle route pour accéder à la page de chaque article, en utilisant la wilde card {id}

    /**
     * @Route("/article/show/{id}", name="article_show")
     */

    // Pour faire afficher sur cette nouvelle page : d'un article, de son titre , de son image , de son contenu.....
    // Je crée une méthode publique : "articleShow" ayant pour multi-paramètres:
    // - la valeur de la wilde card {id} = $id
    // - l'injection de dépendances (class) ou autrement dit, l'autowire ayant pour class : ArticleRepository
    // et pour valeur $articleRepository
    // la class ArticleRepository me permet de faire des requêtes au niveau de ma BDD pour en récupérer
    // les données voulues. Elle me permet de générer des requêtes SELECT dans ma BDD.

    public function articleShow($id, ArticleRepository $articleRepository)
    {
        // Je récupère tous mes articles dans ma BBD
        // en utilisant la méthode publique articleShow et le mécanisme d'autowire de SF
        // ayant pour paramètre la class ArticleRepository et la variable $articleRepository
        // Grâce à la méthode find de la classe ArticleRepository,
        // J'envoie une requête avec un critère : SELECT ..WHERE...pour récuperer l'id de l'article à faire afficher.


        $article = $articleRepository->find($id);

        // Je retourne ma méthode aticleList grâce à la méthode render de la class Abstracontroller,
        // dont j'ai fait hériter la class ArticleController.
        // Je mets en multi-paramètres de la méthode render, le nom du fichier article.html.twig et d'un tableau
        // ayant pour index 'article' le nom de ma variable dans twig et pour valeur : $article (sa valeur dans la BDD).

        return $this->render('front/article.html.twig', [
            'article' => $article
        ]);

    }

}

