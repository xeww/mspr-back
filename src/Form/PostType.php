<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("title", TextType::class, [
                "label" => "Titre du post",
            ])
            ->add("description", TextareaType::class, [
                "label" => "Description du contenu",
            ])
            ->add("content", TextareaType::class, [
                "label" => "Contenu du post",
            ])
            ->add("author", TextType::class, [
                "label" => "Auteur",
            ])
            ->add("imageFile", VichImageType::class, [
                "label" => "Image du post",
                "allow_delete" => false,
                "download_label" => "Télécharger l'image",
            ])
            ->add("submit", SubmitType::class, [
                "label" => "Enregistrer",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => Post::class,
        ]);
    }
}
