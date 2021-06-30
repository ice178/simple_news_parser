<?php

namespace App\Repository;

use App\Entity\ParserLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ParserLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParserLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParserLog[]    findAll()
 * @method ParserLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParserLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParserLog::class);
    }

    // /**
    //  * @return ParserLog[] Returns an array of ParserLog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ParserLog
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
