<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\User;
use App\Form\EmployeType;
use Doctrine\ORM\EntityManagerInterface;
use MongoDB\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin')]
class AdministrateurController extends AbstractController
{
    #[Route('/dashboard', name: 'admin_dashboard')]
    public function dashboard(EntityManagerInterface $em): Response
    {
        $employes = $em->getRepository(Employe::class)->findAll();

        return $this->render('admin/index.html.twig', [
            'employes' => $employes,
        ]);
    }

    #[Route('/employe/ajouter', name: 'admin_employe_ajouter')]
    public function ajouterEmploye(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ): Response {
        $employe = new Employe();
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $hashedPassword = $hasher->hashPassword($employe, $plainPassword);
            $employe->setMotDePasse($hashedPassword);
            $employe->setActif(true);

            $admin = $this->getUser();
            if ($admin) {
                $employe->setCreePar($admin);
            }

            $em->persist($employe);
            $em->flush();

            $this->addFlash('success', 'Employé ajouté avec succès.');
            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/employe_ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/employe/{id}/suspendre', name: 'admin_employe_suspendre')]
    public function suspendreEmploye(Employe $employe, EntityManagerInterface $em): Response
    {
        $employe->setActif(false);
        $em->flush();

        $this->addFlash('warning', 'Le compte employé a été suspendu.');
        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/employe/{id}/reactiver', name: 'admin_employe_reactiver')]
    public function reactiverEmploye(Employe $employe, EntityManagerInterface $em): Response
    {
        $employe->setActif(true);
        $em->flush();

        $this->addFlash('success', 'Le compte employé a été réactivé.');
        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/utilisateur/{id}/suspendre', name: 'admin_user_suspendre')]
    public function suspendreUser(User $user, EntityManagerInterface $em): Response
    {
        $user->setActif(false);
        $em->flush();

        $this->addFlash('warning', 'Le compte utilisateur a été suspendu.');
        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/statistiques', name: 'admin_statistiques')]
    public function statistiques(): Response
    {
        $client = new Client('mongodb://localhost:27017');
        $collection = $client->ecoride->statistiques;

        $donnees = $collection->find([], ['sort' => ['date' => 1]])->toArray();

        $statistiques = [];
        $totalCredits = 0;

        foreach ($donnees as $doc) {
            $date = $doc['date'];
            $credits = $doc['credits_gagnes'] ?? 0;
            $trajets = $doc['nombre_covoiturages'] ?? 0;

            $statistiques[] = [
                'date' => $date,
                'credits_gagnes' => $credits,
                'covoiturages' => $trajets,
            ];

            $totalCredits += $credits;
        }

        return $this->render('admin/statistiques.html.twig', [
            'totalCredits' => $totalCredits,
            'statistiquesJson' => json_encode($statistiques),
        ]);
    }
}
