<?php

namespace App\DataFixtures;

use App\Entity\Preference;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PreferenceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Préférences pour Alice
        $user1 = $this->getReference('user_Alice');

        $pref1 = new Preference();
        $pref1->setUtilisateur($user1);
        $pref1->setFumeur(true);
        $pref1->setAnimaux(false);
        $pref1->setAutresPreferences('Musique calme');
        $manager->persist($pref1);

        $pref2 = new Preference();
        $pref2->setUtilisateur($user1);
        $pref2->setFumeur(false);
        $pref2->setAnimaux(true);
        $pref2->setAutresPreferences('Accepte les pauses');
        $manager->persist($pref2);

        // Préférences pour Bob
        $user2 = $this->getReference('user_Bob');

        $pref3 = new Preference();
        $pref3->setUtilisateur($user2);
        $pref3->setFumeur(false);
        $pref3->setAnimaux(false);
        $pref3->setAutresPreferences('Pas de musique');
        $manager->persist($pref3);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
