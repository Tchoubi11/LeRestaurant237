<?php

namespace App\Controller\Admin;

use App\Entity\Plat;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class PlatCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Plat::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('nom');

        yield ChoiceField::new('type')
            ->setChoices([
                'Entrée' => 'entree',
                'Plat principal' => 'plat',
                'Dessert' => 'dessert',
                'Boisson' => 'boisson',
            ])
            ->renderAsBadges(); 

        yield TextEditorField::new('description');
    }
}