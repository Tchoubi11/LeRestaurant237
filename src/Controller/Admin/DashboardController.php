<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use App\Entity\Plat;
use App\Entity\Allergene;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use App\Controller\Admin\MenuCrudController;
use App\Controller\Admin\PlatCrudController;
use App\Controller\Admin\AllergeneCrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
public function index(): Response
{
    return $this->render('admin/dashboard.html.twig');
}

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('LeRestaurant237 Admin');
    }

    public function configureMenuItems(): iterable
{
    yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

    yield MenuItem::linkToCrud('Menus', 'fas fa-utensils', Menu::class)
        ->setController(MenuCrudController::class);

    yield MenuItem::linkToCrud('Plats', 'fas fa-drumstick-bite', Plat::class)
        ->setController(PlatCrudController::class);

    yield MenuItem::linkToCrud('Allergènes', 'fas fa-exclamation-triangle', Allergene::class)
        ->setController(AllergeneCrudController::class);

    yield MenuItem::linkToRoute('Voir le site', 'fas fa-globe', 'app_home');

    yield MenuItem::linkToCrud('Employés', 'fas fa-users', User::class);

    yield MenuItem::linkToRoute('Statistiques', 'fas fa-chart-bar', 'admin_stats');

    yield MenuItem::linkToRoute('Chiffre d\'affaires', 'fas fa-euro-sign', 'admin_ca');
}
}