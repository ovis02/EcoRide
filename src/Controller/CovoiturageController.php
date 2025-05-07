<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CovoiturageController extends AbstractController
{
    #[Route('/covoiturage', name: 'app_covoiturage')]
    public function index(Request $request): Response
    {
        // Récupération des données du formulaire
        $depart = $request->query->get('depart');
        $destination = $request->query->get('destination');
        $date = $request->query->get('date');
        $passagers = $request->query->get('passagers');

        // Pour tester ce que tu reçois :
        dump($depart, $destination, $date, $passagers); // Symfony Profiler ou debug
        // die(); // à retirer après test

        return $this->render('covoiturage/index.html.twig', [
            'depart' => $depart,
            'destination' => $destination,
            'date' => $date,
            'passagers' => $passagers,
        ]);
    }
}
