<?php
namespace App\Controller;


    use Doctrine\ORM\EntityManagerInterface;
    use App\Form\ArticleType;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use App\Repository\ArticleRepository;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    use App\Entity\Article;

class ArticleController extends AbstractController

{

    /**
     * @Route("/article/list", name="article_list")
     */

    public function articleList(ArticleRepository $articleRepository)
    {
        //Je récupère tous mes articles dans ma BBD
        // en utilisant la méthode publique articleListe et le mécanisme d'autowire de SF
        // ayant pour paramètre la classe Articlerepository et la variable $articleRepository
        // Grâce à la méthode findAll de la classe ArticleRepository,
        //J'envoie une requête SELECT ALL à ma BDD.


        $articles = $articleRepository->findAll();

        //Je retourne grâce à la méthode render de la classe Abstracontroller dont j'ai fait hériter
        //la class ArticleController.
        //je mets en paramètres de la méthode render, le nom du fichier article.html.twig et d'un tableau
        //ayant pour variable 'articles' le nom de ma variable dans twig et $articles sa valeur dans ma BDD

        return $this->render('front/list_articles.html.twig', [
            'articles' => $articles
        ]);
    }

    //Je crée une nouvelle route pour accéder à la page de chaque article, en utilisant la wilde card {id}
    /**
     * @Route("/article/show/{id}", name="article_show")
     */

    //Je crée une méthode publique articleShow ayant pour paramètres, la valeur de la wilde card {id}
    // l'injection de dépendances (class) ou encore l'autowire ayant pour classe ArticleRepository
    // et pour valeur $articleRepository

    public function articleShow($id, ArticleRepository $articleRepository)
    {
        //Je récupère tous mes articles dans ma BBD
        // en utilisant la méthode publique articleShow et le mécanisme d'autowire de SF
        // ayant pour paramètre la class ArticleRepository et la variable $articleRepository
        // Grâce à la méthode find de la classe ArticleRepository,
        //J'envoie une requête SELECT ..WHERE...pour récuperer l'id de l'article à faire afficher.


        $article = $articleRepository->find($id);

        //Je retourne grâce à la méthode render de la classe Abstracontroller dont j'ai fait hériter
        //la class ArticleController.
        //je mets en paramètres de la méthode render, le nom du fichier article.html.twig et d'un tableau
        //ayant pour variable 'article' le nom de ma variable dans twig et $article sa valeur dans ma BDD.

        return $this->render('front/article.html.twig', [
            'article' => $article
        ]);

    }
}