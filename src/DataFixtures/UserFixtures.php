<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        $usersData = [
            [
                'email' => 'alice@example.com',
                'motDePasse' => 'password1',
                'roles' => ['ROLE_CHAUFFEUR'],
            ],
            [
                'email' => 'bob@example.com',
                'motDePasse' => 'password2',
                'roles' => ['ROLE_PASSAGER'],
            ],
            [
                'email' => 'charlie@example.com',
                'motDePasse' => 'password3',
                'roles' => ['ROLE_CHAUFFEUR', 'ROLE_PASSAGER'],
            ],
        ];

        foreach ($usersData as $data) {
            $user = $manager->getRepository(User::class)->findOneBy(['email' => $data['email']]);

            if ($user) {
                // On ne touche pas au mot de passe s’il est déjà haché
                if (!str_starts_with($user->getMotDePasse(), '$2y$')) {
                    $hashedPassword = $this->hasher->hashPassword($user, $data['motDePasse']);
                    $user->setMotDePasse($hashedPassword);
                }

                // Mise à jour des rôles
                $user->setRoles($data['roles']);

                $manager->persist($user);
                $this->addReference('user_' . $user->getEmail(), $user);
            } else {
                echo "⚠️ Utilisateur avec l'email {$data['email']} non trouvé. Aucune mise à jour effectuée.\n";
            }
        }

        $manager->flush();
    }
}
