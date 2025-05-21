<?php

namespace App\DataFixtures;

use App\Entity\Vehicule;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VehiculeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $vehicules = [
            ['marque' => 'Peugeot', 'modele' => '208', 'couleur' => 'Rouge', 'energie' => 'Essence', 'plaque' => 'AB-123-CD', 'date' => new \DateTime('2020-01-15')],
            ['marque' => 'Renault', 'modele' => 'Clio', 'couleur' => 'Bleu', 'energie' => 'Diesel', 'plaque' => 'BC-234-DE', 'date' => new \DateTime('2019-06-01')],
            ['marque' => 'Tesla', 'modele' => 'Model 3', 'couleur' => 'Noir', 'energie' => 'Électrique', 'plaque' => 'CD-345-EF', 'date' => new \DateTime('2022-03-10')],
        ];

        foreach ($vehicules as $i => $data) {
            $vehicule = new Vehicule();
            $vehicule->setMarque($data['marque']);
            $vehicule->setModele($data['modele']);
            $vehicule->setCouleur($data['couleur']);
            $vehicule->setEnergie($data['energie']);
            $vehicule->setPlaqueImmatriculation($data['plaque']);
            $vehicule->setDatePremiereImmatriculation($data['date']);
            $manager->persist($vehicule);

            $this->addReference('vehicule_' . $i, $vehicule);
        }

        $manager->flush();
    }
}
