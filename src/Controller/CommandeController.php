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
    EntityManagerInterface $em,
    \Symfony\Component\Mailer\MailerInterface $mailer
): Response {

    $commande = new Commande();
    $commande->setDateCommande(new \DateTime());
    $commande->setMenu($menu);

    /** @var \App\Entity\User $user */
    $user = $this->getUser();

    $commande->setNomClient($user->getPrenom().' '.$user->getNom());
    $commande->setEmail($user->getEmail());

    $form = $this->createForm(CommandeType::class, $commande);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        $nombre = $commande->getNombrePersonnes();
        $minimum = $menu->getNombrePersonnesMinimum();

        //  Vérification minimum
        if ($nombre < $minimum) {
            $this->addFlash('danger',
                'Le minimum pour ce menu est de '.$minimum.' personnes.');
            return $this->redirectToRoute('app_commande_new', [
                'id' => $menu->getId()
            ]);
        }

        $prixMenu = $menu->getPrix() * $nombre;
        $reduction = 0;

        //  Réduction si +5 personnes au-dessus du minimum
        if ($nombre >= ($minimum + 5)) {
            $reduction = $prixMenu * 0.10;
        }

        $prixLivraison = 5;
        $prixFinal = $prixMenu - $reduction + $prixLivraison;

        $commande->setPrixTotal($prixFinal);
        $commande->setPrixLivraison($prixLivraison);

        $em->persist($commande);
        $em->flush();

        //  Email confirmation
        $email = (new \Symfony\Component\Mime\Email())
            ->from('no-reply@vitetgourmand.com')
            ->to($commande->getEmail())
            ->subject('Confirmation de votre commande')
            ->html("
                <h2>Merci pour votre commande !</h2>
                <p><strong>Menu :</strong> ".$menu->getTitre()."</p>
                <p><strong>Nombre de personnes :</strong> ".$nombre."</p>
                <p><strong>Total :</strong> ".$prixFinal." €</p>
            ");

        $mailer->send($email);

        $this->addFlash('success', 'Commande enregistrée avec succès !');

        return $this->redirectToRoute('app_home');
    }

    return $this->render('commande/new.html.twig', [
        'form' => $form->createView(),
        'menu' => $menu
    ]);
}
}