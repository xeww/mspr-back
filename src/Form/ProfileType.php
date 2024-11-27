<?php

namespace App\Form;

use App\Entity\AdminUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("username", TextType::class, ["label" => "Nom d'utilisateur", "required" => true])
            ->add("firstName", TextType::class, ["label" => "Prénom", "required" => true])
            ->add("lastName", TextType::class, ["label" => "Nom", "required" => true])
            ->add("newPassword", PasswordType::class, ["label" => "Nouveau mot de passe", "mapped" => false, "required" => false])
            ->add("submit", SubmitType::class, ["label" => "Enregistrer"]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => AdminUser::class,
        ]);
    }
}
