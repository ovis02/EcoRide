<?php

namespace App\Controller;

use App\Entity\MessageContact;
use App\Form\MessageContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $message = new MessageContact();
        $form = $this->createForm(MessageContactType::class, $message);
        $form->handleRequest($request);

        // Si AJAX
        if ($request->isXmlHttpRequest()) {
            if ($form->isSubmitted() && $form->isValid()) {
                $message->setDateEnvoi(new \DateTime());
                $em->persist($message);
                $em->flush();

                return new JsonResponse([
                    'success' => true,
                    'message' => 'Votre message a bien été envoyé. Nous vous répondrons par e-mail.'
                ]);
            }

            return new JsonResponse([
                'success' => false,
                'message' => 'Merci de bien remplir tous les champs.'
            ]);
        }

        // Affichage normal de la page (GET)
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
