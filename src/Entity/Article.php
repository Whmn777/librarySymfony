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


/**
 *Je crée donc ma class entité Article, que je mappe grâce à l'annotation  ci dessus, elle est accompagnée
 * par une classe de requête (repository) qui porte le nom de l’entité, suffixée par « Repository:
 *
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */

//Ainsi ma classe entité Article sera scannée par Doctrine pour créer la table associée.
//Ci-dessus chaque mapping correspond à la création dans ma table (côté BDD)
//à la création de de chaque champs : tel que l'id, title....de ma table article.
//on précise par la même occasion le type de la valeur attendue pour chaque champ, si elle peut-être nulle ou pas...

class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publicationDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $creationDate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPublished;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(?\DateTimeInterface $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTimeInterface $creationDate): self
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

