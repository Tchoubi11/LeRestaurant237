<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;

class UserCrudController extends AbstractCrudController
{

    private $passwordHasher;
    private $mailer;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        MailerInterface $mailer
    )
    {
        $this->passwordHasher = $passwordHasher;
        $this->mailer = $mailer;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
{
    return [

        TextField::new('nom')
            ->setLabel('Nom'),

        TextField::new('prenom')
            ->setLabel('Prénom'),

        TextField::new('gsm')
            ->setLabel('Téléphone'),

        TextField::new('adresse')
            ->setLabel('Adresse'),

        EmailField::new('email')
            ->setLabel('Email'),

        TextField::new('password')
            ->setLabel('Mot de passe'),

        BooleanField::new('actif')
            ->setLabel('Compte actif')

    ];
}
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
{
    if (!$entityInstance instanceof User) {
        return;
    }

    // Ici je Hash password seulement si un mot de passe est fourni
    if ($entityInstance->getPassword()) {

        $hashedPassword = $this->passwordHasher->hashPassword(
            $entityInstance,
            $entityInstance->getPassword()
        );

        $entityInstance->setPassword($hashedPassword);
    }

    // Ici je force le ROLE_EMPLOYE
    $entityInstance->setRoles(['ROLE_EMPLOYE']);

    parent::persistEntity($entityManager, $entityInstance);

    // Email envoyé à l'employé
    $email = (new Email())
        ->from('no-reply@vitetgourmand.com')
        ->to($entityInstance->getEmail())
        ->subject('Création de votre compte employé')
        ->html("
            <p>Bonjour,</p>
            <p>Un compte employé a été créé pour vous.</p>
            <p>Veuillez contacter l'administrateur pour obtenir votre mot de passe.</p>
        ");

    $this->mailer->send($email);
}

public function createIndexQueryBuilder(
    SearchDto $searchDto,
    EntityDto $entityDto,
    FieldCollection $fields,
    FilterCollection $filters
): QueryBuilder
{
    $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

    $qb
        ->andWhere('entity.roles LIKE :role')
        ->setParameter('role', '%ROLE_EMPLOYE%');

    return $qb;
}
}