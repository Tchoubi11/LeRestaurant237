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

    #[Route('/menus/{id}', name: 'app_menu_show')]
    public function show(Menu $menu): Response
    {
        return $this->render('menu/show.html.twig', [
            'menu' => $menu
        ]);
    }

    #[Route('/menus/filter', name: 'app_menus_filter')]
    public function filter(Request $request, MenuRepository $repo): JsonResponse
    {
        $maxPrice = $request->query->get('maxPrice');
        $theme = $request->query->get('theme');
        $regime = $request->query->get('regime');

        $menus = $repo->filterMenus($maxPrice, $theme, $regime);

        $data = [];

        foreach ($menus as $menu) {
            $data[] = [
                'id' => $menu->getId(),
                'titre' => $menu->getTitre(),
                'description' => $menu->getDescription(),
                'prix' => $menu->getPrix(),
                'nombrePersonnesMinimum' => $menu->getNombrePersonnesMinimum(),
            ];
        }

        return $this->json($data);
    }
}