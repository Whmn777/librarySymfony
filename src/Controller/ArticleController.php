<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ArticleRepository;
use Symfony\Component\Routing\Annotation\Route;


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

        return $this->render('Articles.html.twig', [
            'articles' => $articles
        ]);
;
    }
}