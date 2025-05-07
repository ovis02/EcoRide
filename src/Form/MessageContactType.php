<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\MessageContact;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('email')
            ->add('message')
            /*
            ->add('dateEnvoi', null, [
                'widget' => 'single_text',
            ])
            ->add('traitePar', EntityType::class, [
                'class' => Employe::class,
                'choice_label' => 'id',
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MessageContact::class,
        ]);
    }
}
