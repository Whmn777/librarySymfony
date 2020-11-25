<?php


namespace App\Controller;


use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    /**
     * Je créee un nouvelle route pour insérer des données statiques (i.e) stockées sur mon Controller.
     *
     * @Route("/category/insert-static", name="category_insert_static")
     */


    //Je créee une méthode publique inserStaticCategory ayant en paramètre la classe EntityManagerInterface qui
    // va me permettre de réaliser des requêtes INSERT, UPDATE et DELETE dans ma BDD. Je rajoute la variable
    //$entityManager

    public function insertStaticCategory(EntityManagerInterface $entityManagerInterface )
    {

        //Dans la variable $categorie, j'instancie un nouvel objet newCategory de la classe entité Category.
        //Je peux ainsi redéfinir à ma class entité des nouvelles propriétés, qui seront insérer comme des
        //nouveaux champs sur ma table categorie dans ma BDD.

        $categorie = new Category();

        //J'utilise des setteurs pour modifier les proriétés de ma class entité Category.

        $categorie->setTitle("Langage de programmation");
        $categorie->setColor("green");
        $categorie->setCreationDate(new \DateTime());
        $categorie->setPublicationDate(new \DateTime());
        $categorie->setIsPublished(true);

        //A ma variable $entityManager, je pré-sauvegarde mes nouvelles données en utilisant la méthode persist
        //de la class EntityManagerInterface) (équivalent du git commit dans Git)

        $entityManagerInterface->persist($categorie);

        //J'insère mes données statiques dans ma table catégorie de ma BDD,
        //en utilisant la méthode flush de la class EntityManagerInterface

        $entityManagerInterface->flush();

        //

        return $this->render('category_insert_static.html.twig');

    }
}
