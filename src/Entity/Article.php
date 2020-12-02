<?php

namespace App\Entity;
//pour représenter une table, j'utilise les entités qui des classes dans PHP.
//Grâce à leur propriété et au mapping, ces entités nous permettent de définir des objets
//Ces objets définissent et décrivent des tables (attributs et comportements) dans ma BDD.

// J'utilise ces deux chemins, ci dessus, afin que je puisse faire appelle
//aux propriétés et méthodes du mapping et de ma class ArticleRepository :
//qui va me permettre de faire des requêtes depuis Symfony directement vers ma BDD dans PHPH MyAdmin.

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Je crée donc ma class entité Article, que je mappe grâce à l'annotation  ci dessus, elle est accompagnée
 * par une classe de requête (repository) qui porte le nom de l’entité, suffixée par « Repository:
 *
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 *
 */

// Ainsi ma classe entité Article sera scannée par Doctrine pour créer la table associée.
// Ci-dessus chaque mapping correspond à la création dans ma table (côté BDD)
// à la création de de chaque champs : tel que l'id, title....de ma table article.
// on précise par la même occasion le type de la valeur attendue pour chaque champ, si elle peut-être nulle ou pas...

class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    // Afin de sécuriser chaque champs à renseigner du formulaire, et contraindre l'utilisateur de saisir un type de
    // données bien défini, on utilise les l'alias de la class Contraints, @Assert. On affiche aussi un message d'erreur,
    // qui sera affiché dans un input grâce à la fonction form_errors grâce au fichier twig associé.
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="Merci de remplir le titre")
     *
     * @Assert\Length(
     *     min=1,
     *     max=255,
     *     minMessage="Veuillez entrez plus de lettres",
     *     maxMessage="Vous avez entrez trop de lettres")
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true, length=500)
     *
     *(Possiblement nul)
     *
     */
    private $content;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\Date(
     *
     *     message="merci de rentrer un date de publication")
     *
     */
    private $publicationDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     *
     * @Assert\Date(
     *
     *  message="Merci d'entrer une date de création")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPublished;


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $imageFileName;

    /**
     * @return mixed
     */
    public function getImageFileName()
    {
        return $this->imageFileName;
    }

    /**
     * @param mixed $imageFileName
     */
    public function setImageFileName($imageFileName): void
    {
        $this->imageFileName = $imageFileName;
    }




    //JOINTURE entre deux tables de la BDD avec Symfony :

    // 1] Lignes de commandes : Générer un foreign key pour et pouvoir anisi relier 2 tables.

    // - bin/console make:entity
    // - NomDeMonEntitéDeDépart (correspond à la table en BDD. Ici : 'Article').
    // - NomDeMaNouvellePropriété (Ici : 'category')
    // - TypeDeRelation (Ici : ManyToOne : car plusieurs articles ne peuvent prendre qu'une catégorie"
    // - NomDeMaClass à relier (Ici : 'Category')
    // Le Terminal relie les deux entités et et crée une nouvelle propriété nommée : (Ici:) 'Article.category
    // Le Terminal me demande si je veux créer aussi pour ma class Category de nouvelles propriétés,
    // qui me permettent de pouvoir récupérer depuis Articles des données de ma table Catégorie =
    // - Y (Je réponds oui !)
    // Le Terminal me demande de faire la même chose (de Articles vers Catégorie).
    // - Y (Je réponds oui !)

    // => Le terminal génère entre mes deux entités une relation, en créant sur ma table de départ ('Articles'),
    //une foreign key de la table d'arrivée ('Categories'), pour identifier un ensemble de colonnes d'une table et
    // référençant une colonne ou un ensemble de colonnes d'une autre table.


    // Avec doctrine on fait ensuite une migration de ses modifications depuis Symfony vers la BDD,
    // avec les lignes de commandes correspondantes :

    // - bin/console make:migrations
    // - bin/console doctrine:migrations:migrate


    /**
     *
     * Je crée une propriété de doctrine pour gérer la relation en les deux tables ici : "ManyToOne"
     * C'est à dire que plusieurs articles peuvent avoir une même catégorie
     * Avec inversedBy doctrine me permet d'obtenir depuis ma table articles, des informations
     * dans ma table catégorie. Je lui attribue la valeur "artcles", qui est la variable privée instanciée
     * dans mon entité Catégory.
     *
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="articles")
     */

    // Puis j'instancie la variable $category, qui va me permettre de relier l'entité Category à Article avec 'mappedBy'.

    private $category;


    /**
     * @return mixed
     */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    //On enlève ?\DateTimeInterface pour ne pas être obligé de saisir dans les champs le type de données :
    public function setPublicationDate( $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    //On enlève ?\DateTimeInterface pour ne pas être obligé de renseigner ce type de données :
    public function setCreationDate( $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(?bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
//Une fois que l’entité est mappée, Il faut demander à Doctrine de scanner nos entités afin
//de générer la requête SQL qui va créer la table associée. Pour cela, on créé un fichier
//« migration ».
//
//php bin/console make:migration
//
//Une fois que la migration (la requête SQL) est générée, on l’exécute en base de
//données :
//
//php bin/console doctrine:migrations:migrate
//
//Pour générer des entités de façon plus rapide, il existe une ligne de commande :
//php bin/console make:entity
//
//Et lorsqu'on a saisi à la main cette entité directement dans SF, il arrive que le la classRepository associée
//ne soit pas directement générer. On effectue alors sur le terminal :
//
//php bin/console make:entity--generate
//

