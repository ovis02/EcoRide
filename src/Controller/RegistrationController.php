<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationForm; 

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        Security $security,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationForm::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération du mot de passe en clair depuis le formulaire
            $plainPassword = $form->get('plainPassword')->getData();

            // Hachage du mot de passe
            $user->setMotDePasse(
                $userPasswordHasher->hashPassword($user, $plainPassword)
            );

            // Définir un rôle par défaut
            $user->setRole('ROLE_USER');

            // Donner un nombre de crédits initial (facultatif)
            $user->setCredits(20);

            // Enregistrement en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Connexion automatique après inscription
            return $security->login($user, 'form_login', 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
