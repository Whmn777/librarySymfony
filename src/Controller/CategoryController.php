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
        // Je récupère toutes mes catégories dans ma BBD
        // en utilisant la méthode publique categoryList et le mécanisme d'autowire de SF
        // ayant pour paramètre la classe CategoryRepository et la variable $categoryRepository
        // Grâce à la méthode findAll de la classe CategoryRepository,
        // J'envoie une requête SELECT ALL à ma BDD.


        $categories = $categoryRepository->findAll();

        // Je retourne grâce à la méthode render de la classe Abstracontroller dont j'ai fait hériter
        // la class CategoryController avec extends.
        // Je mets en paramètres de la méthode render, le nom du fichier category.html.twig et d'un tableau
        // ayant pour variable 'categories' le nom de ma variable dans twig et $categories sa valeur dans ma BDD

        return $this->render('category.html.twig', [
            'categories' => $categories
        ]);

    }

    /**
     * Je créee un nouvelle route pour insérer des données statiques (i.e) stockées sur mon Controller.
     *
     * @Route("/category/insert-static", name="category_insert_static")
     */


    // Je créee une méthode publique inserStaticCategory ayant en paramètre la classe EntityManagerInterface qui
    // va me permettre de réaliser des requêtes INSERT, UPDATE et DELETE dans ma BDD. Je rajoute la variable
    // $entityManager

    public function insertStaticCategory(EntityManagerInterface $entityManagerInterface )
    {

        // Dans la variable $categorie, j'instancie un nouvel objet newCategory de la classe entité Category.
        //J e peux ainsi redéfinir à ma class entité des nouvelles propriétés, qui seront insérer comme des
        // nouveaux champs sur ma table categorie dans ma BDD.

        $categorie = new Category();

        //J'utilise des setteurs pour modifier les proriétés de ma class entité Category.

        $categorie->setTitle("Langage de programmation");
        $categorie->setColor("green");
        $categorie->setCreationDate(new \DateTime());
        $categorie->setPublicationDate(new \DateTime());
        $categorie->setIsPublished(true);

        // A ma variable $entityManager, je pré-sauvegarde mes nouvelles données en utilisant la méthode persist
        // de la class EntityManagerInterface) (équivalent du git commit dans Git)

        $entityManagerInterface->persist($categorie);

        // J'insère mes données statiques dans ma table catégorie de ma BDD,
        // en utilisant la méthode flush de la class EntityManagerInterface

        $entityManagerInterface->flush();

        //

        return $this->render('category_insert_static.html.twig');

    }

    /**
     * Je crée une nouvelle route pour modifier le titre de mes catégories à partir de données statiques que j'ai
     * instanciées dans mon Controller. Pour cela j'ajoute une wildcard id afin de selectionner la catégorie correspondant
     * à modifier.
     *
     * @Route("/category/update-static/{id}", name="category_modify_static")
     */

    // Je créee une méthode publique updateStaticCategory pour :
    // - récuperer chaque category à modifier grâce à la class CategoryRepository, et à l'id,
    // - enregistrer mes modifications grâce à la class EntityManagerInterface et
    // à la variable $entityManager qui va contenir mes modifications.


    public function updateStaticCategory($id,CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        // Je récupère la categorie à modifier de ma BDD, en l'instanciant dans ma variable $category, grâce à la
        // class CategoryRepository. J'utilise la variable $categoryRepository, et la méthode find() pour selectionner
        // l'id de la category recherchée.

        $categorie = $categoryRepository->find($id);

        // Grâce au setteur, je modifie la propriété Title de mon entité $category. Cela correspond à la modification
        // de mes champs et enregistrements de ma table category de ma BDD.

        $categorie->setTitle("Mon nouveau Titre de catégorie");
        $categorie->setColor("purple");
        $categorie->setCreationDate(new \DateTime());
        $categorie->setPublicationDate(new \DateTime());
        $categorie->setIsPublished(true);

        // Avec la class EntityManagerInterface, j'effectue les modifications sur ma table category avec :

        // la méthode persist, qui crée une entrée dans ma table,

        $entityManager->persist($categorie);

        // et la méthode flush(), qui injecte les données et les enregistrent dans ma table article.

        $entityManager->flush();

        // Je retourne ma fonction updateStaticCategory, avec la méthode render, afin de pouvoir l'afficher
        // sur mon navigateur.

        return $this->render("category_update_static.html.twig");

    }
}

