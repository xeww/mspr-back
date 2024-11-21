<?php

namespace App\Form;

use App\Entity\WC;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WCType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("type", TextType::class, [
                "label" => "Type (Mixte, Homme, Femme)",
            ])
            ->add("latitude", TextType::class, [
                "label" => "Latitude des wc",
            ])
            ->add("longitude", TextType::class, [
                "label" => "Longitude des wc",
            ])
            ->add("submit", SubmitType::class, [
                "label" => "Enregistrer",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => WC::class,
        ]);
    }
}
