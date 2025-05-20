<?php

namespace App\Controller;

use App\Entity\Incident;
use App\Entity\Covoiturage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

class IncidentController extends AbstractController
{
    #[Route('/incident/signalement/{id}', name: 'incident_signalement')]
    public function signaler(
        Covoiturage $covoiturage,
        Request $request,
        EntityManagerInterface $em,
        Security $security
    ): Response {
        $user = $security->getUser();

        if (!$user || !in_array('ROLE_PASSAGER', $user->getRoles())) {
            throw $this->createAccessDeniedException('Seuls les passagers peuvent signaler un incident.');
        }

        if ($request->isMethod('POST')) {
            $incident = new Incident();
            $incident->setDescription($request->request->get('description'));
            $incident->setDateDeSignalement(new \DateTime());
            $incident->setCovoiturage($covoiturage);
            $incident->setSignalPar($user);
            $incident->setTraite(false);

            $em->persist($incident);
            $em->flush();

            $this->addFlash('success', 'Le signalement a été transmis.');
            return $this->redirectToRoute('user_history');
        }

        return $this->render('user/incident.html.twig', [
            'covoiturage' => $covoiturage,
        ]);
    }
}
