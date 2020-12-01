<?php


namespace App\Controller;


use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class AdminCategoryController extends AbstractController

{

    /**
     * Je créee une route pour faire afficher la liste de toutes les catégories sur une page du navigateur de l'admin.
     *
     * @Route("/admin/category/list", name="admin_category_list")
     */

    public function categoryList(CategoryRepository $categoryRepository)
    {
        //Je récupère toutes mes catégories dans ma BBD
        // en utilisant la méthode publique categoryList et le mécanisme d'autowire de SF
        // ayant pour paramètre la classe CategoryRepository et la variable $categoryRepository
        // Grâce à la méthode findAll de la classe CategoryRepository,
        //J'envoie une requête SELECT ALL à ma BDD.


        $categories = $categoryRepository->findAll();

        //Je retourne ma méthode categoryList grâce à la méthode render de la classe Abstracontroller pour
        //qui j'ai fait hériter la class CategoryController avec extends.

        //Je mets en paramètres de la méthode render, le nom du fichier category.html.twig et d'un tableau
        //ayant pour variable 'categories' le nom de ma variable dans twig et $categories sa valeur dans ma BDD

        return $this->render('category.html.twig', [
            'categories' => $categories
        ]);

    }


// Dans les trois méthodes suivantes, l'administrateur pourra insérer, modifier, supprimer directement
// des données de la BDD à partir d'un formulaire sur son navigateur, qu'il devra juste renseigner.

// Pour cela, il faut créer un formulaire qui puisse intéragir entre les requêtes de l'administrateur depuis le navigateur
// et la BDD. Ici nous le ferons à partir des lignes de commande du Terminal : déjà commenté dans AdminArticleController


    // A] Pour Insérer des données dans la BDD depuis un formulaire avec SF :

    /**
     * Création de la route :
     *
     * @Route("/admin/category/insert", name="admin_category_insert")
     */

    // Création de la méthode publique "insertCategory" avec en paramètres deux "autowire":
    // -utilisant la classe Request pour récuperer mes requêtes (ici POST);
    // -et la classe EntityManagerInterface pour pouvoir gérer mes données avec la BDD,
    // comme envoyer et enregistrer mes nouvelles données.

    public function insertCategory(Request $request, EntityManagerInterface $entityManager)
    {

        // $article est une nouvelle instance de mon entité Category qui me permet de créer dans ma BDD un nouvel
        // enregistrement.


        $category = new Category();

        // Avec la méthode createForm de la class AbstractController, j'instancie un nouvel objet Form.
        // Je met deux paramètres dans cette méthode:
        // - (le chemin de) la classe Form que je viens de créer en ligne de commande:
        // Ici : Category::class (les deux - deux points signifie tout le chemin qui mène de CategoryType à ma class).
        // - en second paramètre : $category, afin de lier mon formulaire à renseigner à mon nouvel article (encore vide).
        // A $form, je donne la valeur de cette nouvelle instanciation.

        $form = $this->createForm(CategoryType::class, $category);

        // Afin que les nouvelles données saisies lors de la requête soient prises en compte dans ce formulaire,
        // j'utilise la méthode handleRequest de la class Form avec en paramètre $Request(instance de la classe Request)
        // pour relier mon formulaire $form aux nouvelles reqûetes saisies par l'administrateur sur le navigateur.

        $form->handleRequest($request);

        // Pour sécuriser le contenu des envois de données (valeurs non nulles, ou type de données de chaque champs adéquats)
        // J'utilise les méthodes isSubmitted(estSoumis) et isValid(estValidé) de ma class Form,
        // avec une condition :

        // Si le formulaire est Soumis et Validé :

        if ($form->isSubmitted() && $form->isValid()) {
            // alors je l'enregistre en BDD :

            $entityManager->persist($category);
            $entityManager->flush();

            // Puis j'affiche un message de succès de création de catégorie :

            $this->addFlash(
                "success",
                "Bravo : Vous avez bien créer une nouvelle catégorie !!!");
        }

        // Grâce à la méthode creatView de la classe AbstractController, je convertie ma variable $form, en un format
        // lisible par twig.
        // Je nomme donc cette prévisualisation de mon gabbarit $formView

        $formView = $form->createView();

        // Je retourne ma fonction insertArticle, avec la méthode render, afin de pouvoir l'afficher
        // sur mon navigateur, à la page des articles, ainsi que le message 'succès' de création d'article.
        // Pour cela je passe en paramètres de ma méthode render :
        // - mon fichier twig correspondant,
        // - un tableau ayant pour index la variable 'formView' sur twig, qui correspond à la valeur $formView dans php.

        return $this->render('form.categories.html.twig', [
            'formView' => $formView
        ]);
    }

// B] Pour Modifier des données dans la BDD depuis un formulaire avec SF :

    /**
     * Création de la route pour modifier une catégorie depuis la BDD, pour cela il nous faut une wildcard {id}:
     *
     * @Route("/admin/category/update/{id}", name="admin_category_update")
     */

    // Je créé une méthode publique "updateCategory" pour modifier:
    // -  une category spécifique, grâce  à la class CategoryRepository, et à son 'id'.
    // - enregistrer mes modifications grâce à la class EntityManagerInterface et
    // à la variable $entityManager qui va contenir mes modifications.

    public function updateCategory
    (
        $id,
        CategoryRepository $categoryRepository,
        Request $request,
        EntityManagerInterface $entityManager
    )

    {
        $category = $categoryRepository->find($id);

        if (is_null($category)) {
            return $this->redirectToRoute('admin_category_list');
        }

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash(
                "success",
                "La catégorie a été modifié !"
            );

            return $this->redirectToRoute('admin_category_list');
        }

        $formView = $form->createView();

        return $this->render('admin/categories.html.twig', [
            'formView' => $formView
        ]);
    }


// C] Pour Supprimer des données dans la BDD depuis un formulaire avec SF :

    /**
     * Création de la route pour supprimer une  depuis la BDD, pour cela il nous faut une wildcard {id}:
     *
     * @Route("/admin/category/delete/{id}", name="admin_category_delete")
     */

    public function deleteCategory($id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {

        //Je récupère grâce à la valeur $id de la wildcard, et grâce à ma class CategoryRepository la catégorie
        //que je veux supprimer. J'affecte cette $category à la valeur de $categoryRepository, par la méthode find()
        //de la class CategoryRepository.

        $category = $categoryRepository->find($id);

        //Si la valeur de $category n'est pas nulle :
        //avec la class EntityManager, j'instancie la variable $entityManager grâce à la méthode remove:
        //Cette méthode récupère la valeur de ma catégorie et la supprime.
        //Puis avec la méthode flush, ma valeur supprimée donc vide est enregistrée.

        if (!is_null($category)) {
            $entityManager->remove($category);
            $entityManager->flush();

            // J'utilise la méthode addFlash native de Symfony (de la class AbstractController)
            // pour afficher sur ma page de liste de catégories
            //un message flash de type "succès"
            // et signaler à l'utilisateur qu'il a bien supprimé la catégorie.

            $this->addFlash(
                "success",
                "Bravo : Vous avez bien supprimé votre catégorie !!!"
            );
        }

        // J'utilise la méthode redirectToRoute de la class AbstractController
        //pour rediriger l'utilisateur vers la page : liste des catégories,
        // une fois la suppression faite, avec affichage du message de suppression effectué.

        return $this->redirectToRoute("admin_category_list");

    }


}

