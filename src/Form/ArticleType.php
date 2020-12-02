<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('publicationDate', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('creationDate' , DateType::class, [
                'widget' => 'single_text'
            ])

            // Comme l'Entité Category est relié à Article, je peux créer un input pouvant faire
            // afficher les différentes catégories dans le formulaire de saisie et de modifications des articles
            //sous forme d'une liste déroulante.
            // Et parce que Category est une entité, on spécifie par "EntityType::class" le type de cette propriété (du champs).
            // Je choisis quel champs de ma table Catégories je veux voir afficher, donc je précise pour 'choice_label'
            // qu'il s'agit ici du titre de mes catégories.

            ->add('category', EntityType::class, [
                'class'=> Category::class,
                'choice_label' =>'title'
                ])
            //Je rajoute un nouvel input pour mon upload d'image

            ->add('imageFileName', FileType::class, [
                'required' => false, //Je le force à être 'required'.
                'mapped' => false    // Je l'oblige à NE PAS gérer l'upload de l'image directement dans la BDD.
            ])
            ->add('isPublished')
            ->add('valider',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
