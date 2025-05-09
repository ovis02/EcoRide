<?php

namespace App\DataFixtures;

use App\Entity\Covoiturage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CovoiturageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $covoiturages = [
            ['chauffeur' => 'Alice', 'vehicule' => 0, 'depart' => 'Paris', 'arrivee' => 'Lyon', 'date_depart' => '2025-05-10 08:00', 'date_arrivee' => '2025-05-10 12:00', 'places' => 3, 'prix' => 25.0, 'eco' => true],
            ['chauffeur' => 'Charlie', 'vehicule' => 2, 'depart' => 'Marseille', 'arrivee' => 'Nice', 'date_depart' => '2025-05-11 09:30', 'date_arrivee' => '2025-05-11 11:30', 'places' => 2, 'prix' => 18.0, 'eco' => true],
        ];

        foreach ($covoiturages as $data) {
            $covoit = new Covoiturage();
            $covoit->setChauffeur($this->getReference('user_' . $data['chauffeur']));
            $covoit->setVehicule($this->getReference('vehicule_' . $data['vehicule']));
            $covoit->setDepart($data['depart']);
            $covoit->setArrivee($data['arrivee']);
            $covoit->setDateDepart(new \DateTime($data['date_depart']));
            $covoit->setDateArrivee(new \DateTime($data['date_arrivee']));
            $covoit->setNbPlacesDispo($data['places']);
            $covoit->setPrix($data['prix']);
            $covoit->setEstEcologique($data['eco']);
            $manager->persist($covoit);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            VehiculeFixtures::class,
        ];
    }
}
