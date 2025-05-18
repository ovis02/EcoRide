<?php

namespace App\Form;

use App\Entity\Vehicule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('marque')
            ->add('modele')
            ->add('couleur')
            ->add('energie', ChoiceType::class, [
                'choices' => [
                    'Essence' => 'Essence',
                    'Diesel' => 'Diesel',
                    'Électrique' => 'Électrique',
                    'Hybride' => 'Hybride',
                ],
                'placeholder' => 'Choisir une énergie'
            ])
            ->add('plaqueImmatriculation')
            ->add('datePremiereImmatriculation', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de première immatriculation'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
        ]);
    }
}
