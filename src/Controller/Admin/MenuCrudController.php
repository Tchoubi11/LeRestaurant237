<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use App\Controller\Admin\ImageCrudController;

class MenuCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Menu::class;
    }

    public function configureFields(string $pageName): iterable
{
    yield IdField::new('id')->hideOnForm();

    yield TextField::new('titre');

    yield TextEditorField::new('description');

    yield CollectionField::new('images')
        ->useEntryCrudForm(ImageCrudController::class)
        ->allowAdd()
        ->allowDelete()
        ->setFormTypeOption('by_reference', false);
}
}