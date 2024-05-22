<?php

namespace App\Repository;

use App\Entity\HoraireStandard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HoraireStandard>
 *
 * @method HoraireStandard|null find($id, $lockMode = null, $lockVersion = null)
 * @method HoraireStandard|null findOneBy(array $criteria, array $orderBy = null)
 * @method HoraireStandard[]    findAll()
 * @method HoraireStandard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HoraireStandardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HoraireStandard::class);
    }

    //    /**
    //     * @return HoraireStandard[] Returns an array of HoraireStandard objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('h.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?HoraireStandard
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
