<?php

namespace App\Repository;

use App\Entity\ReparationECar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReparationECar|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReparationECar|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReparationECar[]    findAll()
 * @method ReparationECar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReparationECarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReparationECar::class);
    }

    // /**
    //  * @return ReparationECar[] Returns an array of ReparationECar objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReparationECar
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
