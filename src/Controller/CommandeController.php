<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Menu;

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

    #[IsGranted('ROLE_USER')]
#[Route('/commande/new/{id}', name: 'app_commande_new')]
public function new(
    Menu $menu,
    Request $request,
    EntityManagerInterface $em
): Response {

    $commande = new Commande();
    $commande->setDateCommande(new \DateTime());
    $commande->setMenu($menu);

    
    $commande->setNomClient($this->getUser()->getUserIdentifier());
    $commande->setEmail($this->getUser()->getUserIdentifier());

    $form = $this->createForm(CommandeType::class, $commande);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($commande);
        $em->flush();

        $this->addFlash('success', 'Commande enregistrée avec succès !');

        return $this->redirectToRoute('app_menus');
    }

    return $this->render('commande/new.html.twig', [
        'form' => $form->createView(),
        'menu' => $menu
    ]);
}
}