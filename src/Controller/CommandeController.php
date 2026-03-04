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
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use App\Form\AvisType;
use App\Entity\Avis;
use App\Entity\CommandeHistorique;

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

    $commande->setUser($user);
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

        //  Définir le statut initial
    $commande->setStatut('en_attente');

    //  Création de l’historique
    $historique = new CommandeHistorique();
    $historique->setCommande($commande);
    $historique->setStatut('en_attente');
    $historique->setDateModification(new \DateTime());

    // Persist
    $em->persist($commande);
    $em->persist($historique);
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

#[Route('/mon-compte', name: 'app_account')]
#[IsGranted('ROLE_USER')]
public function account(EntityManagerInterface $em): Response
{
    $commandes = $em->getRepository(Commande::class)
        ->findBy(['user' => $this->getUser()], ['dateCommande' => 'DESC']);

    return $this->render('account/index.html.twig', [
        'commandes' => $commandes
    ]);
}

#[Route('/commande/annuler/{id}', name: 'app_commande_annuler')]
#[IsGranted('ROLE_USER')]
public function annuler(Commande $commande, EntityManagerInterface $em)
{
    // Ici on vérifie que la commande appartient à l'utilisateur connecté
    if ($commande->getUser() !== $this->getUser()) {
        throw $this->createAccessDeniedException();
    }

    // Si déjà acceptée → pas annulable
    if ($commande->getStatut() === 'accepte') {
        $this->addFlash('danger', 'Commande déjà acceptée');
        return $this->redirectToRoute('app_account');
    }

    $commande->setStatut('annulee');
    $historique = new CommandeHistorique();
    $historique->setCommande($commande);
    $historique->setStatut('annulee');
    $historique->setDateModification(new \DateTime());

    $em->persist($historique);
    $em->flush();

    $this->addFlash('success', 'Commande annulée avec succès');

    return $this->redirectToRoute('app_account');
}

#[Route('/admin/commande/{id}/terminer', name: 'admin_commande_terminer')]
public function terminer(
    Commande $commande,
    EntityManagerInterface $em,
    MailerInterface $mailer
) {
    $commande->setStatut('terminee');

    $em->flush();

    // Email automatique
    $email = (new Email())
        ->from('no-reply@vitetgourmand.com')
        ->to($commande->getUser()->getEmail())
        ->subject('Votre commande est terminée')
        ->html('
            <h2>Commande terminée </h2>
            <p>Votre commande est maintenant terminée.</p>
            <p>Connectez-vous à votre compte pour laisser un avis.</p>
        ');

    $mailer->send($email);

    $this->addFlash('success', 'Commande marquée comme terminée');

    return $this->redirectToRoute('admin_commandes');
}

#[Route('/commande/{id}/avis', name: 'app_commande_avis')]
#[IsGranted('ROLE_USER')]
public function avis(
    Commande $commande,
    Request $request,
    EntityManagerInterface $em
): Response {

    $user = $this->getUser();


    if ($commande->getUser() !== $user) {
        throw $this->createAccessDeniedException();
    }

    // Doit être terminée
    if ($commande->getStatut() !== 'terminee') {
        $this->addFlash('danger', 'Vous ne pouvez pas encore laisser un avis.');
        return $this->redirectToRoute('app_account');
    }

    if ($commande->getAvis() !== null) {
        $this->addFlash('info', 'Vous avez déjà laissé un avis.');
        return $this->redirectToRoute('app_account');
    }

    $avis = new Avis();
    $avis->setCommande($commande);
    $avis->setUser($user);
    $avis->setDateAvis(new \DateTime());

    $form = $this->createForm(AvisType::class, $avis);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        $em->persist($avis);
        $em->flush();

        $this->addFlash('success', 'Merci pour votre avis ');

        return $this->redirectToRoute('app_account');
    }

    return $this->render('avis/new.html.twig', [
        'form' => $form->createView(),
        'commande' => $commande
    ]);
}

#[Route('/commande/modifier/{id}', name: 'app_commande_modifier')]
public function modifier(Commande $commande): Response
{
    return $this->render('commande/modifier.html.twig', [
        'commande' => $commande
    ]);
}

#[Route('/commande/{id}/suivi', name: 'app_commande_suivi')]
#[IsGranted('ROLE_USER')]
public function suivi(Commande $commande): Response
{
    // Sécurité : empêcher accès à une autre commande
    if ($commande->getUser() !== $this->getUser()) {
        throw $this->createAccessDeniedException();
    }

    return $this->render('commande/suivi.html.twig', [
        'commande' => $commande
    ]);
}
}