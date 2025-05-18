<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Nom d’utilisateur'
            ])
            ->add('email', EmailType::class, [
                'disabled' => true, // L'email n'est pas modifiable ici
            ])
            ->add('motDePasse', PasswordType::class, [
                'label' => 'Nouveau mot de passe',
                'required' => false,
                'mapped' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer les modifications',
                'attr' => ['class' => 'btn btn-primary mt-3']
            ]);
    }
}
