<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Image::class;
    }

    public function configureFields(string $pageName): iterable
    {
    yield IdField::new('id')->hideOnForm();

    yield AssociationField::new('menu');

    yield Field::new('imageFile')
        ->setFormType(VichImageType::class)
        ->onlyOnForms();

    yield ImageField::new('url')
        ->setBasePath('/uploads/menus')
        ->onlyOnIndex();
    }
}
