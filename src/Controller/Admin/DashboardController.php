<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use App\Entity\Plat;
use App\Entity\Allergene;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect(
            $adminUrlGenerator
                ->setController(MenuCrudController::class)
                ->generateUrl()
        );
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('LeRestaurant237 Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::linkToCrud('Menus', 'fas fa-utensils', Menu::class);
        yield MenuItem::linkToCrud('Plats', 'fas fa-drumstick-bite', Plat::class);
        yield MenuItem::linkToCrud('Allergènes', 'fas fa-exclamation-triangle', Allergene::class);

        yield MenuItem::linkToRoute('Voir le site', 'fas fa-globe', 'app_menus');
    }
}