<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class EmployeSecurityController extends AbstractController
{
    #[Route('/employe/login', name: 'employe_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupère la dernière erreur de connexion, s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // Récupère le dernier email saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        // Affiche la page de connexion (le formulaire)
        return $this->render('employe/login.html.twig', [ // Utilise le chemin vers ton template de connexion
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route('/employe/logout', name: 'employe_logout')]
    public function logout(): void
    {
        // Cette méthode est gérée par le firewall de Symfony.  Elle doit rester vide.
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/employe/dashboard', name: 'employe_dashboard')] // Ajoute cette route pour le dashboard
    public function dashboard(): Response
    {
        // Affiche la page de dashboard après la connexion
        return $this->render('employe/index.html.twig'); // Utilise le chemin vers ton template de dashboard
    }
}
