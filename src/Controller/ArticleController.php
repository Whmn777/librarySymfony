<?php


namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
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

        $article->setTitle("Insérer des Données Statiques");
        $article->setContent("J'utilise ma fonction insertArticle, avec pour paramètres la class EntityManagerInterface
          et la variable entityManager");
        $article->setImage("https://secure.meetupstatic.com/photos/event/1/3/c/1/600_467885057.jpeg");
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
        //que veut supprimer. J'affecte cette $article à la valeur de $articleRepository, par la méthode find()
        //de la class ArticleRepository.

        $article = $articleRepository->find($id);

        //Si la valeur de $article n'est pas nulle :
        //avec la class EntityManager, j'instancie la variable $entityManager grâce à la méthode remove:
        //elle récupère la valeur de mon article et la supprimer.
        //Puis avec la méthode flush, ma valeur supprimée donc vide est enregistrée.

        if (!is_null($article)) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->render("delete_article.html.twig");

    }


}