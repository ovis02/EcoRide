<?php

namespace App\DataFixtures;

use App\Entity\Avis;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AvisFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $auteur = $this->getReference('user_Alice');
        $cible = $this->getReference('user_Bob');

        $avis1 = new Avis();
        $avis1->setNote('5');
        $avis1->setCommentaire('Trajet agréable, ponctuel.');
        $avis1->setDate(new \DateTime());
        $avis1->setValide(true);
        $avis1->setAuteur($auteur);
        $avis1->setCible($cible);
        $manager->persist($avis1);

        $avis2 = new Avis();
        $avis2->setNote('4');
        $avis2->setCommentaire('Bonne ambiance, mais un peu en retard.');
        $avis2->setDate(new \DateTime('-2 days'));
        $avis2->setValide(true);
        $avis2->setAuteur($cible);
        $avis2->setCible($auteur);
        $manager->persist($avis2);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}