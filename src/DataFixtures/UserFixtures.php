<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $usersData = [
            ['pseudo' => 'Alice', 'email' => 'alice@example.com', 'motDePasse' => 'password1', 'credits' => 20, 'role' => 'chauffeur'],
            ['pseudo' => 'Bob', 'email' => 'bob@example.com', 'motDePasse' => 'password2', 'credits' => 20, 'role' => 'passager'],
            ['pseudo' => 'Charlie', 'email' => 'charlie@example.com', 'motDePasse' => 'password3', 'credits' => 20, 'role' => 'chauffeur,passager'],
        ];

        foreach ($usersData as $data) {
            $user = new User();
            $user->setPseudo($data['pseudo']);
            $user->setEmail($data['email']);
            $user->setMotDePasse($data['motDePasse']);
            $user->setCredits($data['credits']);
            $user->setRole($data['role']);
            $manager->persist($user);

            $this->addReference('user_' . $data['pseudo'], $user);
        }

        $manager->flush();
    }
}
