<?php

namespace App\Form;

use App\Entity\Covoiturage;
use App\Entity\Vehicule;
use App\Repository\VehiculeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CovoiturageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user']; // On récupère l'utilisateur passé au formulaire

        $builder
            ->add('depart', TextType::class)
            ->add('arrivee', TextType::class)
            ->add('dateDepart', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('dateArrivee', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('nbPlacesDispo', IntegerType::class)
            ->add('prix', MoneyType::class, [
                'currency' => 'EUR',
            ])
            ->add('estEcologique', CheckboxType::class, [
                'required' => false,
                'label' => 'Trajet écologique 🌱',
            ])
            ->add('vehicule', EntityType::class, [
                'class' => Vehicule::class,
                'choice_label' => function (Vehicule $vehicule) {
                    return $vehicule->getMarque() . ' ' . $vehicule->getModele();
                },
                'query_builder' => function (VehiculeRepository $repo) use ($user) {
                    return $repo->createQueryBuilder('v')
                        ->where('v.proprietaire = :user')
                        ->setParameter('user', $user);
                },
                'placeholder' => 'Choisir un véhicule',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Covoiturage::class,
            'user' => null, // on autorise à passer l'utilisateur connecté au formulaire
        ]);
    }
}

