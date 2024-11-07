<?php

namespace App\Form;

use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("name", TextType::class, [
                "label" => "Nom de l'artiste",
            ])
            ->add("description", TextareaType::class, [
                "label" => "Description de l'artiste",
            ])
            ->add("imageFile", VichImageType::class, [
                "label" => "Image/Cover de l'artiste",
                "allow_delete" => false,
                "download_label" => "Télécharger l'image",
            ])
            ->add("submit", SubmitType::class, [
                "label" => "Enregistrer",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => Artist::class,
        ]);
    }
}
