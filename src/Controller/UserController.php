<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/dashboard', name: 'user_dashboard')]
    public function dashboard(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            // Redirection sécurisée si non connecté
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'isChauffeur' => in_array('ROLE_CHAUFFEUR', $user->getRoles()),
            'isPassager' => in_array('ROLE_PASSAGER', $user->getRoles()),
        ]);
    }
}
