<?php

namespace App\Controller;

use App\Repository\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
}