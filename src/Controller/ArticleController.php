<?php


namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ArticleRepository;
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

        return $this->render('articles.html.twig', [
            'articles' => $articles
        ]);
;
    }

    //Je crée une nouvelle route pour accéder à la page de chaque article, en utilisant la wilde card {id}
    /**
     * @Route("/article/show/{id}", name="article_show")
     */

    //Je crée une méthode publique articleShow ayant pour paramètres, la valeur de la wilde card {id}
    // l'injection de dépendances (class) ou encore l'autowire ayant pour classe ArticleRepository
    // et pour valeur $articleRepository

    public function articleShow($id,ArticleRepository $articleRepository)
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

       return $this->render('article.html.twig', [
            'article' => $article
        ]);

    }

    /**
     * Je créee un nouvelle route pour insérer des données statiques (i.e) stockées sur mon Controller.
     *
     * @Route("/article/insert-static", name="article_insert_static")
     */


    //Je créee une méthode publique insertStaticArticle ayant en paramètre la classe EntityManagerInterface qui
    // va me permettre de réaliser des requêtes INSERT, UPDATE et DELETE dans ma BDD. Je rajoute la variable
    //$entityManager

    public function insertStaticArticle(EntityManagerInterface $entityManager )
    {

        //Dans la variable $article, j'instancie un nouvel objet newArticle de la classe entité Article.
        //Je peux ainsi redéfinir à ma class entité des nouvelles propriétés, qui seront insérer comme des
        //nouveaux champs sur ma table article dans ma BDD.

       $article = new Article();

       //J'utilise des setteurs pour modifier les proriétés de ma class entité Article.

        $article->setTitle("Plage de Kiki Peng à Lifou");
        $article->setContent("Rarement référencée dans les guides ou cartes touristiques (celle de la Province 
        des îles ne l’indique pas), la plage de Kiki se dessine le long des falaises de la côte Ouest de Lifou. Perdue
        au milieu de nulle part, la plage est un véritable havre de paix d’une centaine de mètres de longueur, bordée 
        d’une eau turquoise qui laisse rêveur. A noter que les tâches verdâtres dans l’eau (présentes sur les photos 
        ci dessous) sont dues aux algues. De nombreuses personnes nous ont dit être surprises de les voir, c’est donc
        qu’elles ne doivent pas être dans l’eau très souvent.
        Sa particularité ? Elle est cachée au bout d’un chemin en pleine forêt ! 
        Le départ du sentier pour la plage se trouve au niveau d’une maison d’un habitant de Xépénéhé. 
        Plus précisément lorsque vous arrivez au terrain de football. Lorsqu’il est face à vous, rejoignez le coin 
        gauche au fond, la maison est à cet endroit avec un petit panneau « Plage de Kiki ». Comptez 500F/personne. 
        Le sentier (30 minutes de marche) est relativement plat et facilement praticable (sauf 2 ou 3 passages un peu 
        escarpés), pensez tout de même à mettre des chaussures fermées car vous êtes en plein milieu 
        d’une forêt humide !");
        $article->setImage("https://www.unjourencaledonie.com/wp-content/uploads/2017/09/lifou-plage-de-peng_02.jpg");
        $article->setCreationDate(new \DateTime());
        $article->setPublicationDate(new \DateTime());
        $article->setIsPublished(true);

        //A ma variable $entityManager, je pré-sauvegarde mes nouvelles données en utilisant la méthode persist
        //de la class EntityManagerInterface) (équivalent du git commit dans Git)

        $entityManager->persist($article);

        //J'insère mes données statiques dans ma table article de ma BDD,
        //en utilisant la méthode flush de la class EntityManagerInterface

        $entityManager->flush();

        //Je fais un return de ma méthode publique insertStaticArticle en affichant une réponse http sur mon navigateur.
        //Pour cela j'utlise la méthode render de la classe Abstracontroller dont j'ai fait hériter
        //la class ArticleController:
        //je mets en paramètres de la méthode render, le nom du fichier insert.html.twig

        return $this->render('insert_static.html.twig');

    }

    /**
     * Je crée une nouvelle route pour modifier le titre de mes articles à partir de données statiques que j'ai
     * instanciées dans mon Controller. Pour cela j'ajoute une wildcard id afin de selectionner l'article correspondant
     * à modifier.
     *
     * @Route("/article/update-static/{id}", name="article_modify_static")
     */

    //Je créee une méthode publique updateStaticArticle pour :
    // - récuperer chaque article à modifier grâce à la class ArticleRepository, et à l'id,
    // - enregistrer mes modifications grâce à la class EntityManagerInterface et
    // à la variable $entityManager qui va contenir mes modifications.


    public function updateStaticArticle($id, $articleRepository, EntityManagerInterface $entityManager)
    {
        //Je récupère l'article à modifier de ma BDD, en l'instanciant dans ma variable $article, grâce à la
        // class ArticleRepository. J'utilise la variable $articleRepository, et la méthode find() pour selectionner
        //l'id de l'article recherché.

        $article = $articleRepository->find($id);

        //Grâce au setteur, je modifie la propriété Title de mon entité $article. Cela correspond à la modification
        //de mes champs et enregistrements de ma table article de ma BDD.

        $article->setTitle("Mon nouveau Titre");

        //Avec la class EntityManagerInterface, j'effectue les modifications sur ma table articles avec :

        //la méthode persist, qui crée une entrée dans ma table,

        $entityManager->persist($article);

        //et la méthode flush(), qui injecte les données et les enregistrent dans ma table article.

        $entityManager->flush();

        //Je retourne ma fonction updateStaticArticle, avec la méthode render, afin de pouvoir l'afficher
        // sur mon navigateur.

        return $this->render("update_static.html.twig");

    }

    /**
     * Je crée une nouvelle route pour supprimer le titre de mes articles que j'ai
     * selectionné dans mon Controller. Pour cela j'ajoute une wildcard id afin de selectionner l'article correspondant
     * à supprimer.
     *
     * @Route("/article/delete/{id}", name="article_delete")
     *
     */

    //Je créee une méthode publique deleteStaticArticle pour :
    //- supprimer un article  à la class ArticleRepository, et à son 'id'.
    //- enregistrer mes modifications grâce à la class EntityManagerInterface et
    // à la variable $entityManager qui va contenir mes modifications.

    public function deleteArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager)
    {

        //Je récupère grâce à la valeur $id de la wildcard, et grâce à ma class ArticleRepository l'article
        //que je veux supprimer. J'affecte cette $article à la valeur de $articleRepository, par la méthode find()
        //de la class ArticleRepository.

        $article = $articleRepository->find($id);

        //Si la valeur de $article n'est pas nulle :
        //avec la class EntityManager, j'instancie la variable $entityManager grâce à la méthode remove:
        //elle récupère la valeur de mon article et la supprime.
        //Puis avec la méthode flush, ma valeur supprimée donc vide est enregistrée.

        if (!is_null($article)) {
            $entityManager->remove($article);
            $entityManager->flush();

            // J'utilise la méthode addFlash native de Symfony (de la class AbstractController)
            // pour afficher sur ma page de liste d'articles
            //un message flash de type "succès"
            // et signaler à l'utilisateur qu'il a bien supprimé l'article.

            $this->addFlash(
                "success",
                "Bravo : Vous avez bien supprimé cet article !!!"
            );
        }

        // J'utilise la méthode redirectToRoute de la class AbstractController
        //pour rediriger l'utilisateur vers la page : liste d'articles,
        // une fois la suppression faite.

        return $this->redirectToRoute("article_list");

        }

    /**
     * Je créee un nouvelle route pour insérer des données directement depuis un formulaire :
     * (i.e) que l'utilisateur va pouvoir, à partir d'une page formulaire sur le navigateur,
     *  y insérer des données au lieu de le faire depuis le Controller.
     *
     * @Route("/article/insert", name="article_insert")
     */

    //Je créee une méthode publique insertArticle afin de pouvoir créer une page sur mon navigateur affichant
    //le formulaire à renseigner.

    public function insertArticle( )
    {

        //Pour cela, je créee d'abord un gabarit de mon formulaire : j'utilise des lignes de commande sur le Terminal
        //Je m'assure que je suis bien sur mon dossier de projet SF, et je saisis les commandes suivantes:

        //1. bin/console make: form
        // A partir de ma console je crée un dossier Form dans le Controller de mon dossier projet :
        //Cela me permet de créer la class form qui va me permettre de générer mon gabarit sur SF.
        //
        //Je nomme donc ma class en utilisant le nom de mon entité (où je dois modifier les propriétés) suivi de Type
        //Je saisi donc sur mon Terminal:
        //2. EntityNameType (Je crée dans Form, un fichier que nomme "EntityName" suivi de "Type". Ici "Article".
        //
        //Je renseigne dans mon Terminal le nom de mon Entité qui sera utiliser par SF pour
        // générer depuis mon Controller, mon gabarit. Les propiétés de mon Entité seront scannées et vont générer
        //les champs à renseigner de mon formulaire.
        //
        //3.Je saisi donc mon Entity sur mon Terminal : Ici "Article".


        //Soit la variable $form correspondant au gabbarit que je veux générer.
        //Je lui donne la valeur d'un nouveau gabbarit que j'instancie par la méthode createForm de la classe
        //AbstractController. Je lui met en paramètre (le chemin) la classe form que je viens de créer en ligne de commande.
        //Ici : ArticleType::class (les deux - deux points signifie tout le chamin qui mène de ArticleType à ma class)

        $form = $this->createForm(ArticleType::class);

        //Grâce à la méthode creatView de la classe AbstractController, je convertis ma variable $form, en un format
        // lisible par twig.
        // Je nomme donc cette prévisualisation de mon gabbarit $formView

        $formView = $form->createView();

        //Je retourne ma fonction insertArticle, avec la méthode render, afin de pouvoir l'afficher en htpp
        // sur mon navigateur.

        return $this->render('article/insert.html.twig', [
            'formView' => $formView
        ]);
    }

}