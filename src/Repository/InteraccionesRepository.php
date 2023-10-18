<?php

namespace App\Repository;

use App\Entity\Interacciones;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Interacciones>
 *
 * @method Interacciones|null find($id, $lockMode = null, $lockVersion = null)
 * @method Interacciones|null findOneBy(array $criteria, array $orderBy = null)
 * @method Interacciones[]    findAll()
 * @method Interacciones[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InteraccionesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Interacciones::class);
    }

//    /**
//     * @return Interacciones[] Returns an array of Interacciones objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Interacciones
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
