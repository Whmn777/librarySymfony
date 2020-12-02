<?php


namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\String\Slugger\SluggerInterface;


class AdminArticleController extends AbstractController

{

    /**
     * Je créee une route pour faire afficher la liste de tous les articles sur une page du navigateur de l'admin.
     *
     * @Route("/admin/article/list", name="admin_article_list")
     */


    // Puis je récupère tous mes articles dans ma BBD
    // en utilisant la méthode publique "articleList" et le mécanisme d'autowire de SF
    // ayant pour paramètres la classe Articlerepository et la variable $articleRepository.
    // Grâce à la méthode "findAll" de la classe ArticleRepository,
    // J'envoie une requête SELECT ALL à ma BDD pour récupérer tous les articles de ma table.


    public function articleList(ArticleRepository $articleRepository)
    {

        $articles = $articleRepository->findAll();

        // Je retourne ma méthode "articleList" grâce à la méthode render de la classe Abstracontroller
        // pour qui j'ai fait hériter la class AdminArticleController.

        // Je mets en paramètres de la méthode render, le nom du fichier admnin_article.html.twig et d'un tableau
        // ayant pour variable 'articles' le nom de ma variable dans twig et $articles sa valeur dans ma BDD

        return $this->render('admin/articles.html.twig', [
            'articles' => $articles
        ]);

    }


// Dans les deux méthodes suivantes, il s'agit d'insérer, et de modifier ma BDD, à partir de données statiques,
// (i.e) de données stockées directement sur mon Controller.


    /**
     * Je créee un nouvelle route pour insérer des données statiques (stockées sur mon Controller).
     *
     * @Route("/admin/article/insert-static", name="admin_article_insert_static")
     */


    // Je créee une méthode publique "insertStaticArticle" ayant en paramètre la class EntityManagerInterface qui
    // va me permettre de réaliser des requêtes INSERT, UPDATE et DELETE dans ma BDD. Je rajoute la variable
    // $entityManager

    public function insertStaticArticle(EntityManagerInterface $entityManager)
    {

        // Dans la variable $article, j'instancie un nouvel objet newArticle de la classe entité Article.
        // Je peux ainsi redéfinir à ma class entité des nouvelles propriétés, qui seront insérer comme des
        // nouveaux champs sur ma table article dans ma BDD.

        $article = new Article();

        // J'utilise des setteurs pour modifier les proriétés de ma class entité Article.

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

        // A ma variable $entityManager, je pré-sauvegarde mes nouvelles données en utilisant la méthode persist
        // de la class EntityManagerInterface) (équivalent du git commit dans Git). la méthode persist me prépare
        // les entrées de mes nouvelles données dans chaque champs de ma BDD.

        $entityManager->persist($article);

        // J'insère et enregistre mes données statiques dans ma table article de ma BDD,
        // en utilisant la méthode flush de la class EntityManagerInterface

        $entityManager->flush();

        // J'utilise la méthode addFlash native de Symfony (de la class AbstractController)
        // pour afficher sur ma page de liste d'articles
        // un message flash de type "succès"
        // et signaler à l'utilisateur qu'il a bien insérer l'article.

        $this->addFlash(
            "success",
            "Bravo : Vous avez bien créer un nouvel article !!!");

        // Je fais un return de ma méthode publique insertStaticArticle en affichant une réponse http sur mon navigateur.
        // Pour cela j'utlise la méthode render de la class Abstracontroller dont j'ai fait hériter
        // la class ArticleController:
        // je mets en paramètres de la méthode render, le nom du fichier front.articles.html.twig.
        // Ma réponse http affichera la page de tous les articles avec le message 'succès' de création d'article.

        return $this->render('admin/articles.html.twig');

    }


    /**
     * Je crée une nouvelle route pour modifier le titre de mes articles à partir de données statiques que j'ai
     * instanciées dans mon Controller. Pour cela j'ajoute une wildcard id afin de selectionner l'article correspondant
     * à modifier.
     *
     * @Route("/admin/article/update-static/{id}", name="admin_article_modify_static")
     */

    // Je créee une méthode publique "updateStaticArticle" pour :
    // - Récuperer chaque article à modifier grâce à la class ArticleRepository (qui va me générer des requêtes
    // dans ma BDD, et cela à partir de la valeur de ma wild card $id).
    // - Enregistrer mes modifications grâce à la class EntityManagerInterface et
    // à la variable $entityManager qui va contenir mes modifications.


    public function updateStaticArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager)
    {
        // Je créee une instance $articleRepository pour récupérer l'article à modifier de ma BDD
        // grâce à la méthode find() de la class ArticleRepository.
        // Je fais donc passer à cette méthode le paramètre $id.
        // Nous utilisons ici le mécanisme d'autowire de SF, pour instancier un nouvel objet à partir d'une classe :
        // (i.e) lorsque la classe (ici : ArticleRepository, et, ses instantiations (ici : $articleRepository) sont en
        // paramètres de la méthode.
        // L'autowire génère un "new" objet de la classe.

        $article = $articleRepository->find($id);

        //Si l'article n'existe pas dans ma BDD (i.e) que l'id n'existe pas et par conséquent $article a pour valeur
        //false soit "0" : avec la méthode redirectToRoute de la class AbstractController, l'administrateur sera
        // redirigé vers la page de liste des articles.

        if (is_null($article)) {
            return $this->redirectToRoute('admin_article_list');
        }


        // Grâce au setteur, je modifie la propriété Title de mon entité $article. Cela correspond à la modification
        // de mes champs et enregistrements de ma table "articles" de ma BDD.

        $article->setTitle("Mon nouveau Titre");


        // Avec la class EntityManagerInterface, j'effectue les modifications sur ma table "articles" en BDD avec :

        // la méthode persist, qui crée une entrée dans ma table,

        $entityManager->persist($article);


        // et la méthode flush(), qui injecte les données et les enregistrent dans ma table "articles".

        $entityManager->flush();


        // J'utilise la méthode addFlash native de Symfony (de la class AbstractController)
        // pour afficher sur ma page de liste d'articles
        // un message flash de type "succès"
        // et signaler à l'utilisateur qu'il a bien insérer l'article.

        $this->addFlash(
            "success",
            "Bravo : Vous avez bien modifier votre article !!!");

        // Je retourne ma fonction updateStaticArticle, avec la méthode render, afin de pouvoir l'afficher
        // sur mon navigateur, ainsi que le message 'succès' de modification de l'article.

        return $this->render("admin/articles.html.twig");


        // Ces deux méthodes d'insertion et de modification statiques ne sont jamais voir rarement utilisées,
        // elles nous servent juste ici de modèles de compréhension  sur la logique des mécanismes d'insertion
        // et de modification de données dans la BDD avec SF.
        // Ici aucune sécurité n'a été mise en place pour protéger notre site.


    }


// Dans les trois méthodes suivantes, l'administrateur pourra insérer, modifier, supprimer directement
// des données de la BDD à partir d'un formulaire sur son navigateur, qu'il devra juste renseigner.

// La logique et les mécanismes restent presque similaires.

//  Je ne commenterai donc que les nouvelles notions qui diffèrent des logiques précédentes pour ne pas me répéter.

// Pour cela, il faut créer un formulaire qui puisse intéragir entre les requêtes de l'administrateur depuis le navigateur
// et la BDD. Ici nous le ferons à partir des lignes de commande du Terminal :

    // Je m'assure d'abord que je suis bien sur mon dossier de projet SF, et je saisis les commandes suivantes:

    // 1. bin/console make: form

    // Ici, depuis ma console je crée un dossier Form dans le Controller de mon dossier projet SF :
    // Cela me permet de créer la class form qui va me générer mon gabarit sur SF.

    // Je nomme donc ma class Form en utilisant le nom de mon entité (celle pour qui  je dois en modifier
    // les propriétés) suivi de "Type".
    // Je saisi donc sur mon Terminal:

    // 2. EntityNameType (Je crée dans Form, un fichier que je nomme "EntityName" suivi de "Type". Ici "ArticleType".)

    // Je renseigne ensuite dans mon Terminal le nom de mon Entité qui sera utiliser par SF pour
    // créer depuis mon Controller, mon gabarit : Les propiétés de mon Entité seront scannées pour générer
    // les champs de ce qui deviendra mon formulaire, à renseigner sur le navigateur.

    // 3.Je saisis donc mon Entity sur mon Terminal : Ici "Article".

// A ce stade, depuis le Terminal, le dossier "Form" contenant le fichier ArticleType sont crées dans le dossier "src" de
// mon projet SF. Le fichier "ArticleType" qui n'est autre qu'une class enfant de la class Form, contient toutes les
// propriétés et méthodes pour générer un gabbarit de mon nouveau formulaire.


// A] Pour Insérer des données dans la BDD depuis un formulaire avec SF :

    /**
     * Création de la route :
     *
     * @Route("/admin/article/insert", name="admin_article_insert")
     */

    // Création de la méthode publique "insertArticle" avec en paramètres deux "autowire":
    // -utilisant la classe Request pour récuperer mes requêtes (ici POST);
    // -et la classe EntityManagerInterface pour pouvoir gérer mes données avec la BDD,
    // comme envoyer et enregistrer mes nouvelles données.

    public function insertArticle(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {

        // $article est une nouvelle instance de mon entité Article qui me permet de créer dans ma BDD un nouvel
        // enregistrement.


        $article = new Article();

        //Puis, je créee d'abord un gabarit de mon formulaire : j'utilise des lignes de commande sur le Terminal
        //Je m'assure que je suis bien sur mon dossier de projet SF, et je saisis les commandes suivantes:

        //1. bin/console make: form
        // A partir de ma console je crée un dossier Form dans le Controller de mon dossier projet :
        //Cela me permet de créer la class form qui va me permettre de générer mon gabarit sur SF.
        //
        //Je nomme donc ma class en utilisant le nom de mon entité (où je dois modifier les propriétés) suivi de Type
        //Je saisi donc sur mon Terminal:
        //2. EntityNameType (Je crée dans Form, un fichier que je nomme "EntityName" suivi de "Type". Ici "Article".
        //
        //Je renseigne dans mon Terminal le nom de mon Entité qui sera utiliser par SF pour
        // générer depuis mon Controller, mon gabarit. Les propiétés de mon Entité seront scannées et vont générer
        //les champs à renseigner de mon formulaire.
        //
        //3.Je saisis donc mon Entity sur mon Terminal : Ici "Article".

        // Avec la méthode createForm de la class AbstractController, j'instancie un nouvel objet Form.
        // Je met deux paramètres dans cette méthode:
        // - (le chemin de) la classe Form que je viens de créer en ligne de commande:
        // Ici : ArticleType::class (les deux - deux points signifie tout le chemin qui mène de ArticleType à ma class).
        // - en second paramètre : $article, afin de lier mon formulaire à renseigner à mon nouvel article (encore vide).
        // A $form, je donne la valeur de cette nouvelle instanciation.

        $form = $this->createForm(ArticleType::class, $article);

        // Afin que les nouvelles données saisies lors de la requête soient prises en compte dans ce formulaire,
        // j'utilise la méthode handleRequest de la class Form avec en paramètre $Request(instance de la classe Request)
        // pour relier mon formulaire $form aux nouvelles reqûetes saisies par l'administrateur sur le navigateur.

        $form->handleRequest($request);

        // Pour sécuriser le contenu des envois de données (valeurs non nulles, ou type de données de chaque champs adéquats)
        // J'utilise les méthodes isSubmitted(estSoumis) et isValid(estValidé) de ma class Form,
        // avec une condition :

        // Si le formulaire est Soumis et Validé :

        if ($form->isSubmitted() && $form->isValid()) {
            // 1. Je récupère le nom de mon image de mon input de formulaire, et les datas du fichier.
            $imageFile = $form->get('imageFileName')->getData();

            // 2. Si le nom du fichier avec ou sans contenu est récupérer :

            if ($imageFile) {

                // 2A] je ne récupère d'abord que le nom du fichier image :
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                // 2B] Avec la méthode slug de la class Slugger, je sors tous les caractères spéciaux de mon nom de fichier :
                $safeFilename = $slugger->slug($originalFilename);

                // 2C] Je lui attribue un nom unique avec la méthode uniqid(), et lui concatène son extension :
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();


                // 2D] Je déplace l'image dans un dossier que j'ai spécifié en paramètre
                // (dans le fichier config/services.yaml)
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );

                // 3. J'enregistre ensuite ce nouveau 'slug' dans mon entité :
                $article->setImageFileName($newFilename);

                // Je l'enregistre en BDD :

                $entityManager->persist($article);
                $entityManager->flush();

                // Puis j'affiche un message de succès de création d'article :

                $this->addFlash(
                    "success",
                    "Bravo : Vous avez bien créer un nouvel article !!!");
            }


            return $this->redirectToRoute('admin_article_list');

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

            return $this->render('admin/form.html.twig', [
                'formView' => $formView
            ]);

    }

// B] Pour Modifier des données dans la BDD depuis un formulaire avec SF :

        /**
         * Création de la route pour modifier un article depuis la BDD, pour cela il nous faut une wildcard {id}:
         *
         * @Route("/admin/article/update/{id}", name="admin_article_update")
         */

        // Je créé une méthode publique "updateArticle" pour modifier:
        // -  un article spécifique, grâce  à la class ArticleRepository, et à son 'id'.
        // - enregistrer mes modifications grâce à la class EntityManagerInterface et
        // à la variable $entityManager qui va contenir mes modifications.


        public function updateArticle
        (
            $id,
            ArticleRepository $articleRepository,
            Request $request,
            EntityManagerInterface $entityManager
        )

        {
            $article = $articleRepository->find($id);

            if (is_null($article)) {
                return $this->redirectToRoute('admin_article_list');
            }

            $form = $this->createForm(ArticleType::class, $article);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($article);
                $entityManager->flush();

                $this->addFlash(
                    "success",
                    "L'article a été modifié !"
                );

                return $this->redirectToRoute('admin_article_list');
            }

            $formView = $form->createView();

            return $this->render('admin/articles.html.twig', [
                'formView' => $formView
            ]);
        }



// C] Pour Supprimer des données dans la BDD depuis un formulaire avec SF :

        /**
         * Création de la route pour supprimer un article depuis la BDD, pour cela il nous faut une wildcard {id}:
         *
         * @Route("/admin/article/delete/{id}", name="admin_article_delete")
         */

        public
        function deleteArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager)
        {

            //Je récupère grâce à la valeur $id de la wildcard, et grâce à ma class ArticleRepository l'article
            //que je veux supprimer. J'affecte cette $article à la valeur de $articleRepository, par la méthode find()
            //de la class ArticleRepository.

            $article = $articleRepository->find($id);

            //Si la valeur de $article n'est pas nulle :
            //avec la class EntityManager, j'instancie la variable $entityManager grâce à la méthode remove:
            //Cette méthode récupère la valeur de mon article et la supprime.
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
            //pour rediriger l'utilisateur vers la page : liste des articles,
            // une fois la suppression faite, avec affichage du message de suppression effectué.

            return $this->redirectToRoute("admin_article_list");

        }



}
