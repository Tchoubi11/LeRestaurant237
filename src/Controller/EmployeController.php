<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Commande;
use App\Repository\CommandeRepository;
use App\Entity\CommandeHistorique;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Avis;
use App\Repository\AvisRepository;

final class EmployeController extends AbstractController
{
    #[Route('/employe', name: 'app_employe')]
    #[IsGranted('ROLE_EMPLOYE')]
    public function index(): Response
    {
        return $this->render('employe/index.html.twig');
    }

    // ================================
    // LISTE + FILTRE COMMANDES
    // ================================
    #[Route('/employe/commandes', name: 'app_employe_commandes')]
    #[IsGranted('ROLE_EMPLOYE')]
    public function commandes(Request $request, CommandeRepository $repo): Response
    {
        $statut = $request->query->get('statut');
        $client = $request->query->get('client');

        $qb = $repo->createQueryBuilder('c');

        if ($statut) {
            $qb->andWhere('c.statut = :statut')
               ->setParameter('statut', $statut);
        }

        if ($client) {
            $qb->andWhere('c.nomClient LIKE :client')
               ->setParameter('client', '%'.$client.'%');
        }

        $commandes = $qb->orderBy('c.dateCommande', 'DESC')
                        ->getQuery()
                        ->getResult();

        return $this->render('employe/commandes.html.twig', [
            'commandes' => $commandes
        ]);
    }

    // ================================
    // CHANGEMENT DE STATUT
    // ================================
    #[Route('/employe/commande/{id}/statut/{statut}', name: 'app_employe_commande_statut')]
#[IsGranted('ROLE_EMPLOYE')]
public function changerStatut(
    Commande $commande,
    string $statut,
    EntityManagerInterface $em,
    MailerInterface $mailer
): Response {

    //  Empêcher modification si déjà finalisée
    if (in_array($commande->getStatut(), ['terminee', 'annulee'])) {
        throw $this->createAccessDeniedException('Commande déjà finalisée.');
    }

    //  Statuts autorisés uniquement
    $statutsAutorises = [
        'accepte',
        'en_preparation',
        'en_livraison',
        'livre',
        'retour_materiel',
        'terminee'
    ];

    if (!in_array($statut, $statutsAutorises)) {
        throw $this->createAccessDeniedException('Statut non autorisé.');
    }

    // Mise à jour statut
    $commande->setStatut($statut);

    // Ajout historique
    $historique = new CommandeHistorique();
    $historique->setCommande($commande);
    $historique->setStatut($statut);
    $historique->setDateModification(new \DateTime());

    $em->persist($commande);
    $em->persist($historique);
    $em->flush();

    //  Email automatique si retour matériel
    if ($statut === 'retour_materiel') {

        $email = (new Email())
            ->from('no-reply@vitetgourmand.com')
            ->to($commande->getEmail())
            ->subject('Retour de matériel requis')
            ->html("
                <p>Merci de restituer le matériel sous 10 jours ouvrés.</p>
                <p>Sans restitution, 600€ de frais seront appliqués.</p>
            ");

        $mailer->send($email);
    }

    return $this->redirectToRoute('app_employe_commandes');
}
    // ================================
    // ANNULATION AVEC MOTIF
    // ================================
    #[Route('/employe/commande/{id}/annuler', name: 'app_employe_commande_annuler')]
#[IsGranted('ROLE_EMPLOYE')]
public function annuler(
    Commande $commande,
    Request $request,
    EntityManagerInterface $em,
    MailerInterface $mailer
): Response {

    //  Empêcher annulation si déjà livrée ou terminée
    if (in_array($commande->getStatut(), ['livre', 'terminee', 'annulee'])) {
        throw $this->createAccessDeniedException('Impossible d\'annuler cette commande.');
    }

    if ($request->isMethod('POST')) {

        $motif = $request->request->get('motif');
        $modeContact = $request->request->get('mode_contact');

        //  Vérification obligatoire
        if (!$motif || !$modeContact) {
            $this->addFlash('danger', 'Motif et mode de contact obligatoires.');
            return $this->redirectToRoute('app_employe_commandes');
        }

        // Mise à jour commande
        $commande->setStatut('annulee');
        $commande->setMotifAnnulation($motif);
        $commande->setModeContact($modeContact);

        // Historique
        $historique = new CommandeHistorique();
        $historique->setCommande($commande);
        $historique->setStatut('annulee');
        $historique->setDateModification(new \DateTime());

        $em->persist($commande);
        $em->persist($historique);
        $em->flush();

        //  Email client
        $email = (new Email())
            ->from('no-reply@vitetgourmand.com')
            ->to($commande->getEmail())
            ->subject('Commande annulée')
            ->html("
                <p>Votre commande a été annulée.</p>
                <p><strong>Mode de contact :</strong> $modeContact</p>
                <p><strong>Motif :</strong> $motif</p>
            ");

        $mailer->send($email);

        return $this->redirectToRoute('app_employe_commandes');
    }

    return $this->render('employe/annuler.html.twig', [
        'commande' => $commande
    ]);
}
    // ================================
    // VALIDATION AVIS
    // ================================
    #[Route('/employe/avis/{id}/valider', name: 'app_employe_avis_valider')]
    #[IsGranted('ROLE_EMPLOYE')]
    public function validerAvis(Avis $avis, EntityManagerInterface $em): Response
    {
        $avis->setValide(true);
        $em->flush();

        return $this->redirectToRoute('app_employe');
    }

    // ================================
    // REFUS AVIS
    // ================================
    #[Route('/employe/avis/{id}/refuser', name: 'app_employe_avis_refuser')]
    #[IsGranted('ROLE_EMPLOYE')]
    public function refuserAvis(Avis $avis, EntityManagerInterface $em): Response
    {
        $em->remove($avis);
        $em->flush();

        return $this->redirectToRoute('app_employe');
    }

    #[Route('/employe/avis', name: 'app_employe_avis')]
#[IsGranted('ROLE_EMPLOYE')]
public function listeAvis(AvisRepository $avisRepository): Response
{
    $avis = $avisRepository->findBy(['valide' => false]);

    return $this->render('employe/avis.html.twig', [
        'avis' => $avis
    ]);
}
}