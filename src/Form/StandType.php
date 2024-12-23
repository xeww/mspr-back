<?php

namespace App\Form;

use App\Entity\Stand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("name", TextType::class, [
                "label" => "Nom du stand",
            ])
            ->add("latitude", TextType::class, [
                "label" => "Latitude du stand",
            ])
            ->add("longitude", TextType::class, [
                "label" => "Longitude du stand",
            ])
            ->add("submit", SubmitType::class, [
                "label" => "Enregistrer",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => Stand::class,
        ]);
    }
}
