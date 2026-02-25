<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Repository\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class MenuController extends AbstractController
{
   #[Route('/menus', name: 'app_menus')]
public function index(MenuRepository $menuRepository): Response
{
    return $this->render('menu/index.html.twig', [
        'menus' => $menuRepository->findAll()
    ]);
}

#[Route('/menus/filter', name: 'app_menus_filter')]
public function filter(Request $request, MenuRepository $repo): JsonResponse
{
    $maxPrice = $request->query->get('maxPrice');

    $qb = $repo->createQueryBuilder('m');

    if ($maxPrice !== null && $maxPrice !== '') {
        $qb->where('m.prix <= :maxPrice')
           ->setParameter('maxPrice', (float) $maxPrice);
    }

    $menus = $qb->getQuery()->getResult();

    $data = [];

    foreach ($menus as $menu) {
        $firstImage = $menu->getImages()->first();

        $data[] = [
            'id' => $menu->getId(),
            'titre' => $menu->getTitre(),
            'description' => $menu->getDescription(),
            'prix' => $menu->getPrix(),
            'nombrePersonnesMinimum' => $menu->getNombrePersonnesMinimum(),
            'image' => $firstImage ? $firstImage->getUrl() : null,
        ];
    }

    return $this->json($data);
}

#[Route('/menus/{id<\d+>}', name: 'app_menu_show')]
public function show(Menu $menu): Response
{
    return $this->render('menu/show.html.twig', [
        'menu' => $menu
    ]);
}
}