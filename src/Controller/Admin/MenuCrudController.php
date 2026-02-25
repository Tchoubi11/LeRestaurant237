<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class MenuCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Menu::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('titre');

        yield TextEditorField::new('description');

        yield MoneyField::new('prix')
            ->setCurrency('EUR')
            ->setStoredAsCents(false);

        yield IntegerField::new('nombrePersonnesMinimum')
            ->setLabel('Nombre minimum de personnes');

        yield IntegerField::new('stock')
            ->setHelp('Nombre de menus disponibles');

        yield ChoiceField::new('theme')
            ->setChoices([
                'Mariage' => 'mariage',
                'Anniversaire' => 'anniversaire',
                'Entreprise' => 'entreprise',
                'Baptême' => 'bapteme',
                'Classique' => 'classique',
                'Prestige' => 'prestige',
            ]);

        yield ChoiceField::new('regime')
            ->setChoices([
                'Classique' => 'classique',
                'Végétarien' => 'vegetarien',
                'Halal' => 'halal',
                'Sans porc' => 'sans_porc',
                'Sans gluten' => 'sans_gluten',
                'Vegan' => 'vegan',
            ]);

        yield TextEditorField::new('conditionsMenu')
            ->setRequired(false)
            ->hideOnIndex();

        yield CollectionField::new('images')
        ->useEntryCrudForm(ImageCrudController::class)
        ->allowAdd()
        ->allowDelete()
        ->setFormTypeOption('by_reference', false);

        yield DateTimeField::new('dateCreation')
            ->hideOnForm();
    }
}