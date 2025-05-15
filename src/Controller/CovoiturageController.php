<?php

namespace App\Controller;

use App\Entity\Covoiturage;
use App\Repository\CovoiturageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CovoiturageController extends AbstractController
{
    #[Route('/covoiturage', name: 'app_covoiturage', methods: ['GET'])]
    public function index(Request $request, CovoiturageRepository $covoiturageRepository): Response
    {
        $depart = trim((string) $request->query->get('depart', ''));
        $destination = trim((string) $request->query->get('destination', ''));
        $date = $request->query->get('date', '');
        $passagers = is_numeric($request->query->get('passagers')) ? (int)$request->query->get('passagers') : null;

        $eco = $request->query->get('eco');
        $prixMax = is_numeric($request->query->get('prixMax')) ? (float)$request->query->get('prixMax') : null;
        $dureeMax = is_numeric($request->query->get('dureeMax')) ? (int)$request->query->get('dureeMax') : null;
        $noteMin = is_numeric($request->query->get('note')) ? (int)$request->query->get('note') : null;
        $eco = ($eco === '1' || $eco === '0') ? (int)$eco : null;

        $dateObject = null;
        if (!empty($date)) {
            $dateObject = \DateTime::createFromFormat('Y-m-d', $date);
            if (!$dateObject) {
                $this->addFlash('warning', 'Format de date invalide.');
                return $this->redirectToRoute('app_covoiturage');
            }
        }

        $covoiturages = $covoiturageRepository->findFiltered(
            $depart ?: null,
            $destination ?: null,
            $dateObject ? $dateObject->format('Y-m-d') : null,
            $passagers
        );

        if ($dureeMax !== null) {
            $covoiturages = array_filter($covoiturages, function ($c) use ($dureeMax) {
                $diff = $c->getDateDepart()->diff($c->getDateArrivee());
                $hours = $diff->h + $diff->days * 24;
                return $hours <= $dureeMax;
            });
        }

        if ($prixMax !== null) {
            $covoiturages = array_filter($covoiturages, fn($c) => $c->getPrix() <= $prixMax);
        }

        if ($eco !== null) {
            $covoiturages = array_filter($covoiturages, fn($c) => $c->isEstEcologique() === (bool)$eco);
        }

        return $this->render('covoiturage/index.html.twig', [
            'covoiturages' => $covoiturages,
            'search' => compact('depart', 'destination', 'date', 'passagers', 'eco', 'prixMax', 'dureeMax', 'noteMin'),
        ]);
    }

    #[Route('/covoiturage/participer/{id}', name: 'covoiturage_participer')]
    public function participer(
        Request $request,
        Covoiturage $covoiturage,
        EntityManagerInterface $em,
        CsrfTokenManagerInterface $csrfTokenManager
    ): Response {
        $user = $this->getUser();

        // MODALE 3 — Utilisateur non connecté
        if (!$user) {
            return $this->render('covoiturage/modales/modal_3.html.twig', [
                'covoiturage' => $covoiturage,
            ]);
        }

        // ❌ Si l'utilisateur est uniquement chauffeur, participation refusée
        if (
            in_array('ROLE_CHAUFFEUR', $user->getRoles(), true) &&
            !in_array('ROLE_PASSAGER', $user->getRoles(), true)
        ) {
            return $this->render('covoiturage/modales/modal_4.html.twig', [
                'covoiturage' => $covoiturage,
                'erreurRole' => 'En tant que chauffeur uniquement, vous ne pouvez pas participer à un covoiturage.',
            ]);
        }

        // MODALE 4 — Affichage confirmation
        if (!$request->isMethod('POST')) {
            return $this->render('covoiturage/modales/modal_4.html.twig', [
                'covoiturage' => $covoiturage,
            ]);
        }

        // Vérification CSRF
        $submittedToken = $request->request->get('_token');
        $expectedToken = new CsrfToken('participer' . $covoiturage->getId(), $submittedToken);

        if (!$csrfTokenManager->isTokenValid($expectedToken)) {
            throw new AccessDeniedException('Jeton CSRF invalide.');
        }

        // Vérification crédits et places
        if ($covoiturage->getNbPlacesDispo() < 1 || $user->getCredits() < 1) {
            return $this->redirectToRoute('app_covoiturage');
        }

        // Déjà passager ?
        if ($covoiturage->getPassagers()->contains($user)) {
            return $this->redirectToRoute('app_covoiturage');
        }

        // ✅ Enregistrement
        $covoiturage->addPassager($user);
        $covoiturage->setNbPlacesDispo($covoiturage->getNbPlacesDispo() - 1);
        $user->setCredits($user->getCredits() - 1);

        $em->flush();

        // MODALE 5 — Confirmation
        return $this->render('covoiturage/modales/modal_5.html.twig', [
            'covoiturage' => $covoiturage,
        ]);
    }
}