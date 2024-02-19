<?php

namespace App\Repository;

use App\Entity\Admission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Admission>
 *
 * @method Admission|null find($id, $lockMode = null, $lockVersion = null)
 * @method Admission|null findOneBy(array $criteria, array $orderBy = null)
 * @method Admission[]    findAll()
 * @method Admission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdmissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Admission::class);
    }

//    /**
//     * @return admission[] Returns an array of admission objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?admission
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
