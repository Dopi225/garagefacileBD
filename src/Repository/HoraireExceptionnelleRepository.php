<?php

namespace App\Repository;

use App\Entity\HoraireExceptionnelle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HoraireExceptionnelle>
 *
 * @method HoraireExceptionnelle|null find($id, $lockMode = null, $lockVersion = null)
 * @method HoraireExceptionnelle|null findOneBy(array $criteria, array $orderBy = null)
 * @method HoraireExceptionnelle[]    findAll()
 * @method HoraireExceptionnelle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HoraireExceptionnelleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HoraireExceptionnelle::class);
    }

    //    /**
    //     * @return HoraireExceptionnelle[] Returns an array of HoraireExceptionnelle objects
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

    //    public function findOneBySomeField($value): ?HoraireExceptionnelle
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
