<?php

namespace App\Controller\Admin;

use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class StatsController extends AbstractController
{

#[Route('/admin/stats', name: 'admin_stats')]
public function stats(CommandeRepository $repo): Response
{
    $stats = $repo->countCommandesByMenu();

    return $this->render('admin/stats.html.twig', [
        'stats' => $stats
    ]);
}

#[Route('/admin/chiffre-affaires', name: 'admin_ca')]
public function chiffreAffaires(CommandeRepository $repo): Response
{
    $ca = $repo->chiffreAffairesParMenu();

    return $this->render('admin/ca.html.twig', [
        'ca'=>$ca
    ]);
}

}