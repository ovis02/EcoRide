<?php

namespace App\Controller;

use App\Repository\AvisRepository;
use App\Repository\IncidentRepository;
use App\Repository\MessageContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class EmployeSecurityController extends AbstractController
{
    #[Route('/employe/login', name: 'employe_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('employe/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/employe/logout', name: 'employe_logout')]
    public function logout(): void
    {
        throw new \LogicException('Cette méthode peut rester vide : elle est interceptée par le firewall.');
    }

    #[Route('/employe/dashboard', name: 'employe_dashboard')]
    public function dashboard(
        AvisRepository $avisRepository,
        IncidentRepository $incidentRepository,
        MessageContactRepository $messageContactRepository
    ): Response {
        //  Avis à valider
        $avis_a_valider = $avisRepository->findBy(['valide' => false]);

        // Incidents non traités
        $incidents = $incidentRepository->findBy(['traite' => false]);

        // Tous les messages, pour afficher aussi ceux déjà traités
        $messages = $messageContactRepository->findAll();

        return $this->render('employe/index.html.twig', [
            'avis_a_valider' => $avis_a_valider,
            'incidents' => $incidents,
            'messages' => $messages,
        ]);
    }
}
