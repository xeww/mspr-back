<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\Concert;
use App\Entity\Scene;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConcertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("artist", EntityType::class, [
                "class" => Artist::class,
                "choice_label" => "name",
                "label" => "Artiste"
            ])
            ->add("scene", EntityType::class, [
                "class" => Scene::class,
                "choice_label" => "name",
                "label" => "Scène"
            ])
            ->add("dateAndTime", DateTimeType::class, [
                "label" => "Date et heure",
            ])
            ->add("submit", SubmitType::class, [
                "label" => "Enregistrer",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => Concert::class,
        ]);
    }
}
