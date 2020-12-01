<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $color;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publicationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $CreationDate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPublished;




    /**
     * Je créee une propriété, avec doctrine pour gérer la relation en les deux tables ici : "OneToMany"
     * C'est à dire su'une catégorie peut avoir plusieurs articles.
     * Avec mappedBy doctrine me permet d'obtenir depuis ma table catégories, des informations
     * de ma table article. Je lui attribue la valeur "category", qui est la variable privée instanciée
     * dans mon entité Catégory. Cette valeur sera appelée par IndversedBy dans l'entité "Article".
     *
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="category")
     */


    private $articles;


    //J'utilise la méthode constructor, lorsque je veux instancier un nouvel objet ( avec new).

    // Ici, j'instancie depuis la class ArrayCollection de Doctrine, un nouvel objet qui est un tableau associé à des méthodes
    // spécifiques, pour intéragir avec les données de ce même tableau.

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

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
        return $this->CreationDate;
    }

    public function setCreationDate(?\DateTimeInterface $CreationDate): self
    {
        $this->CreationDate = $CreationDate;

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

    /**
     * @return Collection|Article[]
     */


    public function getArticles(): Collection
    {
        return $this->articles;
    }

    // Quand je veux insérer un nouvel article dans ma table, la méthode addArticle (avec en paramètre l'auto wire de
    // class Article et variable $article) me permet de modifier qu'une ligne d'enregistrements sans écraser toutes les
    // autres. Nous avons ici en type de donnée un tableau, il faut donc isoler toutes nos données de la seule modification
    // à apporter, et que seule cette modification soit enregistrée. Toutes les autres données restent inchangées et
    // non impactées.


    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setCategory($this);
        }

        return $this;
    }

    // Avec la méthode removeArticle on fait la même chose mais pour supprimer des données (sans changer les autres)

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getCategory() === $this) {
                $article->setCategory(null);
            }
        }

        return $this;
    }
}
