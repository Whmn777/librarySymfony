<?php


namespace App\Controller;


use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Category;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends AbstractController

{

    /**
     * @Route("/category/list", name="category_list")
     */

    public function categoryList(CategoryRepository  $categoryRepository)
    {
        //Je récupère toutes mes catégories dans ma BBD
        // en utilisant la méthode publique categoryList et le mécanisme d'autowire de SF
        // ayant pour paramètre la classe CategoryRepository et la variable $categoryRepository
        // Grâce à la méthode findAll de la classe CategoryRepository,
        //J'envoie une requête SELECT ALL à ma BDD.


        $categories = $categoryRepository->findAll();

        //Je retourne grâce à la méthode render de la classe Abstracontroller dont j'ai fait hériter
        //la class CategoryController avec extends.
        //Je mets en paramètres de la méthode render, le nom du fichier category.html.twig et d'un tableau
        //ayant pour variable 'categories' le nom de ma variable dans twig et $categories sa valeur dans ma BDD

        return $this->render('category.html.twig', [
            'categories' => $categories
        ]);

    }
}