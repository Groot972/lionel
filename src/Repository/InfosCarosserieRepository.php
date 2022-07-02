<?php

namespace App\Repository;

use App\Entity\InfosCarosserie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InfosCarosserie|null find($id, $lockMode = null, $lockVersion = null)
 * @method InfosCarosserie|null findOneBy(array $criteria, array $orderBy = null)
 * @method InfosCarosserie[]    findAll()
 * @method InfosCarosserie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfosCarosserieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InfosCarosserie::class);
    }

    // /**
    //  * @return InfosCarosserie[] Returns an array of InfosCarosserie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InfosCarosserie
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
