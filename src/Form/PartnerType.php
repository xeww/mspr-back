<?php

namespace App\Form;

use App\Entity\Partner;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PartnerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("name", TextType::class, [
                "label" => "Nom du partenaire",
            ])
            ->add("type", ChoiceType::class, [
                "label" => "Type de partenaire",
                "choices"  => [
                    "Numérique" => "Numérique",
                    "Alimentaire" => "Alimentaire",
                    "Local" => "Local",
                    "Média" => "Média",
                    "Transport" => "Transport",
                ],
            ])
            ->add("imageFile", VichImageType::class, [
                "label" => "Image du logo",
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
            "data_class" => Partner::class,
        ]);
    }
}
