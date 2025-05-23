<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\MessageContact;
use App\Entity\Incident;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeController extends AbstractController
{
    // 🔹 Valider un avis
    #[Route('/employe/avis/{id}/valider', name: 'employe_valider_avis', methods: ['GET'])]
    public function validerAvis(Avis $avis, EntityManagerInterface $em): Response
    {
        $avis->setValide(true);
        $avis->setGerePar($this->getUser());
        $em->flush();

        $this->addFlash('success', 'Avis validé avec succès.');
        return $this->redirectToRoute('employe_dashboard');
    }

    // 🔹 Supprimer un avis
    #[Route('/employe/avis/{id}/supprimer', name: 'employe_supprimer_avis', methods: ['GET'])]
    public function supprimerAvis(Avis $avis, EntityManagerInterface $em): Response
    {
        $em->remove($avis);
        $em->flush();

        $this->addFlash('success', 'Avis supprimé.');
        return $this->redirectToRoute('employe_dashboard');
    }

    // 🔹 Marquer un message de contact comme traité
    #[Route('/employe/message/{id}/traite', name: 'employe_traite_message', methods: ['GET'])]
    public function traiterMessage(MessageContact $message, EntityManagerInterface $em): Response
    {
        $message->setTraite(true);
        $message->setTraitePar($this->getUser());
        $em->flush();

        $this->addFlash('success', 'Message marqué comme traité.');
        return $this->redirectToRoute('employe_dashboard');
    }

    // 🔹 Supprimer un message de contact
    #[Route('/employe/message/{id}/supprimer', name: 'employe_supprimer_message', methods: ['GET'])]
    public function supprimerMessage(MessageContact $message, EntityManagerInterface $em): Response
    {
        $em->remove($message);
        $em->flush();

        $this->addFlash('success', 'Message supprimé.');
        return $this->redirectToRoute('employe_dashboard');
    }

    // 🔹 Voir un incident signalé
    #[Route('/employe/incident/{id}', name: 'employe_voir_incident', methods: ['GET'])]
    public function voirIncident(Incident $incident): Response
    {
        return $this->render('employe/incident.html.twig', [
            'incident' => $incident
        ]);
    }

    // 🔹 Marquer un incident comme traité
    #[Route('/employe/incident/{id}/traite', name: 'employe_traite_incident', methods: ['GET'])]
    public function traiterIncident(Incident $incident, EntityManagerInterface $em): Response
    {
        $incident->setTraite(true);
        $em->flush();

        $this->addFlash('success', 'Incident marqué comme traité.');
        return $this->redirectToRoute('employe_dashboard');
    }

    // 🔹 Supprimer un incident
    #[Route('/employe/incident/{id}/supprimer', name: 'employe_supprimer_incident', methods: ['GET'])]
    public function supprimerIncident(Incident $incident, EntityManagerInterface $em): Response
    {
        $em->remove($incident);
        $em->flush();

        $this->addFlash('success', 'Incident supprimé.');
        return $this->redirectToRoute('employe_dashboard');
    }
}
