<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $commande = new Commande();
        $commande->setDateCommande(new \DateTime());

        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($commande);
            $em->flush();

            $this->addFlash('success', 'Commande enregistrée avec succès !');

            return $this->redirectToRoute('app_commande');
        }

        return $this->render('commande/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
