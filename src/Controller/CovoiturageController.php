<?php

namespace App\Controller;

use App\Repository\CovoiturageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CovoiturageController extends AbstractController
{
    #[Route('/covoiturage', name: 'app_covoiturage', methods: ['GET'])]
    public function index(Request $request, CovoiturageRepository $covoiturageRepository): Response
    {
        $depart = $request->query->get('depart', '');
        $destination = $request->query->get('destination', '');
        $date = $request->query->get('date', '');
        $passagers = $request->query->get('passagers', '');

        $eco = $request->query->get('eco');
        $prixMax = $request->query->get('prixMax');
        $dureeMax = $request->query->get('dureeMax');
        $noteMin = $request->query->get('note');

        $depart = is_string($depart) ? trim($depart) : '';
        $destination = is_string($destination) ? trim($destination) : '';
        $passagers = is_numeric($passagers) ? (int)$passagers : null;

        $eco = ($eco === '1' || $eco === '0') ? (int)$eco : null;
        $prixMax = is_numeric($prixMax) ? (float)$prixMax : null;
        $dureeMax = is_numeric($dureeMax) ? (int)$dureeMax : null;
        $noteMin = is_numeric($noteMin) ? (int)$noteMin : null;

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

        // Filtrage PHP : Duree max
        if ($dureeMax !== null) {
            $covoiturages = array_filter($covoiturages, function ($c) use ($dureeMax) {
                $diff = $c->getDateDepart()->diff($c->getDateArrivee());
                $hours = $diff->h + $diff->days * 24;
                return $hours <= $dureeMax;
            });
        }

        // Filtrage PHP : Prix max
        if ($prixMax !== null) {
            $covoiturages = array_filter($covoiturages, function ($c) use ($prixMax) {
                return $c->getPrix() <= $prixMax;
            });
        }

        // Filtrage PHP : Eco
        if ($eco !== null) {
            $covoiturages = array_filter($covoiturages, function ($c) use ($eco) {
                return $c->isEstEcologique() === (bool)$eco;
            });
        }

        return $this->render('covoiturage/index.html.twig', [
            'covoiturages' => $covoiturages,
            'search' => [
                'depart' => $depart,
                'destination' => $destination,
                'date' => $date,
                'passagers' => $passagers,
                'eco' => $eco,
                'prixMax' => $prixMax,
                'dureeMax' => $dureeMax,
                'noteMin' => $noteMin,
            ],
        ]);
    }
}
