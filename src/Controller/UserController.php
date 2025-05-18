<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Avis;
use App\Entity\Vehicule;
use App\Entity\Covoiturage;
use App\Form\UserProfileType;
use App\Form\VehiculeType;
use App\Form\CovoiturageType;
use App\Form\AvisType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/dashboard', name: 'user_dashboard')]
    public function dashboard(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'isChauffeur' => in_array('ROLE_CHAUFFEUR', $user->getRoles()),
            'isPassager' => in_array('ROLE_PASSAGER', $user->getRoles()),
        ]);
    }

    #[Route('/ajouter-role/{role}', name: 'ajouter_role', methods: ['POST'])]
    public function ajouterRole(string $role, EntityManagerInterface $em): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $roles = $user->getRoles();

        if ($role === 'chauffeur' && !in_array('ROLE_CHAUFFEUR', $roles)) {
            $roles[] = 'ROLE_CHAUFFEUR';
        } elseif ($role === 'passager' && !in_array('ROLE_PASSAGER', $roles)) {
            $roles[] = 'ROLE_PASSAGER';
        } elseif ($role === 'both') {
            if (!in_array('ROLE_CHAUFFEUR', $roles)) {
                $roles[] = 'ROLE_CHAUFFEUR';
            }
            if (!in_array('ROLE_PASSAGER', $roles)) {
                $roles[] = 'ROLE_PASSAGER';
            }
        }

        $user->setRoles(array_unique($roles));
        $em->flush();

        $this->addFlash('success', 'Votre rôle a bien été mis à jour !');
        return $this->redirectToRoute('user_dashboard');
    }

    #[Route('/mon-profil', name: 'user_profile')]
    public function profile(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('motDePasse')->getData();

            if ($newPassword) {
                $user->setMotDePasse($hasher->hashPassword($user, $newPassword));
            }

            $em->flush();
            $this->addFlash('success', 'Profil mis à jour avec succès.');
            return $this->redirectToRoute('user_profile');
        }

        return $this->render('user/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/mes-vehicules', name: 'user_vehicles')]
    public function vehicles(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $vehicules = $em->getRepository(Vehicule::class)->findBy([
            'proprietaire' => $user
        ]);

        return $this->render('user/vehicles.html.twig', [
            'vehicules' => $vehicules
        ]);
    }

    #[Route('/mes-vehicules/ajouter', name: 'user_vehicles_add')]
    public function addVehicle(Request $request, EntityManagerInterface $em): Response
    {
        $vehicule = new Vehicule();
        $vehicule->setProprietaire($this->getUser());

        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($vehicule);
            $em->flush();

            $this->addFlash('success', 'Véhicule ajouté avec succès.');
            return $this->redirectToRoute('user_vehicles');
        }

        return $this->render('user/vehicule_form.html.twig', [
            'form' => $form->createView(),
            'editMode' => false
        ]);
    }

    #[Route('/mes-vehicules/{id}/modifier', name: 'user_vehicles_edit')]
    public function editVehicle(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $vehicule = $em->getRepository(Vehicule::class)->find($id);

        if (!$vehicule || $vehicule->getProprietaire() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Ce véhicule ne vous appartient pas.");
        }

        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Véhicule modifié avec succès.');
            return $this->redirectToRoute('user_vehicles');
        }

        return $this->render('user/vehicule_form.html.twig', [
            'form' => $form->createView(),
            'editMode' => true
        ]);
    }

    #[Route('/mes-vehicules/{id}/supprimer', name: 'user_vehicles_delete', methods: ['POST'])]
    public function deleteVehicle(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $vehicule = $em->getRepository(Vehicule::class)->find($id);

        if (!$vehicule || $vehicule->getProprietaire() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Accès refusé.");
        }

        if ($this->isCsrfTokenValid('delete' . $vehicule->getId(), $request->request->get('_token'))) {
            $em->remove($vehicule);
            $em->flush();
            $this->addFlash('success', 'Véhicule supprimé avec succès.');
        }

        return $this->redirectToRoute('user_vehicles');
    }

 #[Route('/mes-trajets', name: 'user_trips')]
    public function trips(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $trajetsChauffeur = $em->getRepository(Covoiturage::class)->findBy(['chauffeur' => $user]);

        $trajetsPassager = $em->getRepository(Covoiturage::class)
            ->createQueryBuilder('c')
            ->join('c.passagers', 'p')
            ->where('p = :user')
            ->andWhere('c.chauffeur != :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        return $this->render('user/trips.html.twig', [
            'trajetsChauffeur' => $trajetsChauffeur,
            'trajetsPassager' => $trajetsPassager
        ]);
    }

    #[Route('/trajet/creer', name: 'trajet_create')]
    public function createTrajet(Request $request, EntityManagerInterface $em): Response
    {
        $trajet = new Covoiturage();
        $user = $this->getUser();
        $trajet->setChauffeur($user);

        $form = $this->createForm(CovoiturageType::class, $trajet, [
            'user' => $user
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getCredits() < 2) {
                $this->addFlash('error', 'Vous n\'avez pas assez de crédits pour créer un trajet.');
                return $this->redirectToRoute('user_trips');
            }

            $user->setCredits($user->getCredits() - 2);
            $em->persist($trajet);
            $em->flush();

            $this->addFlash('success', 'Trajet créé avec succès. 2 crédits ont été déduits.');
            return $this->redirectToRoute('user_trips');
        }

        return $this->render('user/trajet_form.html.twig', [
            'form' => $form->createView(),
            'editMode' => false
        ]);
    }

    #[Route('/trajet/{id}/modifier', name: 'trajet_edit')]
    public function editTrajet(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $trajet = $em->getRepository(Covoiturage::class)->find($id);

        if (!$trajet || $trajet->getChauffeur() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Ce trajet ne vous appartient pas.");
        }

        $form = $this->createForm(CovoiturageType::class, $trajet, [
            'user' => $this->getUser()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Trajet modifié avec succès.');
            return $this->redirectToRoute('user_trips');
        }

        return $this->render('user/trajet_form.html.twig', [
            'form' => $form->createView(),
            'editMode' => true
        ]);
    }

    #[Route('/trajet/{id}/supprimer', name: 'trajet_delete', methods: ['POST'])]
    public function deleteTrajet(int $id, Request $request, EntityManagerInterface $em): RedirectResponse
    {
        $trajet = $em->getRepository(Covoiturage::class)->find($id);

        if (!$trajet || $trajet->getChauffeur() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Accès refusé.");
        }

        if ($this->isCsrfTokenValid('delete' . $trajet->getId(), $request->request->get('_token'))) {
            $em->remove($trajet);
            $em->flush();
            $this->addFlash('success', 'Trajet supprimé avec succès.');
        }

        return $this->redirectToRoute('user_trips');
    }

    #[Route('/trajet/{id}/quitter', name: 'trajet_quit', methods: ['POST'])]
    public function quitTrajet(int $id, Request $request, EntityManagerInterface $em): RedirectResponse
    {
        $trajet = $em->getRepository(Covoiturage::class)->find($id);
        $user = $this->getUser();

        if (!$trajet || !$trajet->getPassagers()->contains($user)) {
            throw $this->createAccessDeniedException("Ce trajet ne vous concerne pas.");
        }

        if ($this->isCsrfTokenValid('quit' . $trajet->getId(), $request->request->get('_token'))) {
            $trajet->removePassager($user);
            $em->flush();
            $this->addFlash('success', 'Vous avez quitté le trajet.');
        }

        return $this->redirectToRoute('user_trips');
    }

    #[Route('/historique', name: 'user_history')]
    public function history(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $covoiturages = $em->getRepository(Covoiturage::class)
            ->createQueryBuilder('c')
            ->leftJoin('c.passagers', 'p')
            ->where('c.chauffeur = :user OR p = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        $historiques = [];

        foreach ($covoiturages as $covoiturage) {
            $role = ($covoiturage->getChauffeur() === $user) ? 'chauffeur' : 'passager';
            $avis = $em->getRepository(Avis::class)->findOneBy([
                'auteur' => $user,
                'cible' => $role === 'passager' ? $covoiturage->getChauffeur() : null,
            ]);

            $historiques[] = [
                'id' => $covoiturage->getId(),
                'depart' => $covoiturage->getDepart(),
                'arrivee' => $covoiturage->getArrivee(),
                'dateDepart' => $covoiturage->getDateDepart(),
                'etat' => $covoiturage->getEtat(),
                'role' => $role,
                'avis' => $avis,
                'chauffeur' => $covoiturage->getChauffeur(),
            ];
        }

        return $this->render('user/history.html.twig', [
            'historiques' => $historiques,
        ]);
    }

    #[Route('/trajet/{id}/start', name: 'trajet_start')]
public function startTrajet(int $id, EntityManagerInterface $em): RedirectResponse
{
    $trajet = $em->getRepository(Covoiturage::class)->find($id);

    if (!$trajet || $trajet->getChauffeur() !== $this->getUser()) {
        throw $this->createAccessDeniedException("Vous n'avez pas le droit de démarrer ce trajet.");
    }

    if ($trajet->getEtat() !== 'prévu') {
        $this->addFlash('warning', 'Ce trajet ne peut pas être démarré.');
    } else {
        $trajet->setEtat('en_cours');
        $em->flush();
        $this->addFlash('success', 'Trajet démarré avec succès.');
    }

    return $this->redirectToRoute('user_history');
}

#[Route('/trajet/{id}/end', name: 'trajet_end')]
public function endTrajet(int $id, EntityManagerInterface $em): RedirectResponse
{
    $trajet = $em->getRepository(Covoiturage::class)->find($id);

    if (!$trajet || $trajet->getChauffeur() !== $this->getUser()) {
        throw $this->createAccessDeniedException("Vous n'avez pas le droit de terminer ce trajet.");
    }

    if ($trajet->getEtat() !== 'en_cours') {
        $this->addFlash('warning', 'Ce trajet ne peut pas être marqué comme terminé.');
    } else {
        $trajet->setEtat('termine');
        $em->flush();
        $this->addFlash('success', 'Trajet terminé. Les passagers peuvent maintenant valider le trajet et laisser un avis.');
    }

    return $this->redirectToRoute('user_history');
}


     #[Route('/trajet/{id}/avis', name: 'trajet_avis')]
    public function donnerAvis(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $trajet = $em->getRepository(Covoiturage::class)->find($id);
        $user = $this->getUser();

        if (!$trajet || !$trajet->getPassagers()->contains($user)) {
            throw $this->createAccessDeniedException("Vous ne pouvez pas évaluer ce trajet.");
        }

        $chauffeur = $trajet->getChauffeur();
        $avis = new Avis();
        $avis->setAuteur($user);
        $avis->setCible($chauffeur);
        $avis->setDate(new \DateTime());
        $avis->setValide(false);

        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($avis);
            $em->flush();
            $this->addFlash('success', 'Avis envoyé pour validation.');
            return $this->redirectToRoute('user_history');
        }

        return $this->render('user/avis_form.html.twig', [
            'form' => $form->createView(),
            'cible' => $chauffeur
        ]);
    }
}