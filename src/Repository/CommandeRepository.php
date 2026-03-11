<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commande>
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    public function countCommandesByMenu()
{
    return $this->createQueryBuilder('c')
        ->select('m.titre as menu, COUNT(c.id) as total')
        ->join('c.menu', 'm')
        ->groupBy('m.id')
        ->getQuery()
        ->getResult();
}

public function chiffreAffairesParMenu()
{
    return $this->createQueryBuilder('c')
        ->select('m.titre as menu, SUM(c.prixTotal) as chiffre')
        ->join('c.menu','m')
        ->groupBy('m.id')
        ->getQuery()
        ->getResult();
}

    //    /**
    //     * @return Commande[] Returns an array of Commande objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Commande
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
