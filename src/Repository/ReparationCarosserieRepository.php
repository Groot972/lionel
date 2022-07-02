<?php

namespace App\Repository;

use App\Entity\ReparationCarosserie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReparationCarosserie|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReparationCarosserie|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReparationCarosserie[]    findAll()
 * @method ReparationCarosserie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReparationCarosserieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReparationCarosserie::class);
    }

    // /**
    //  * @return ReparationCarosserie[] Returns an array of ReparationCarosserie objects
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
    public function findOneBySomeField($value): ?ReparationCarosserie
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
