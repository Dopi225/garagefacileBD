<?php

namespace App\Repository;

use App\Entity\Disponibilite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Disponibilite>
 *
 * @method Disponibilite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Disponibilite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Disponibilite[]    findAll()
 * @method Disponibilite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DisponibiliteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Disponibilite::class);
    }

    //    /**
    //     * @return Disponibilite[] Returns an array of Disponibilite objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Disponibilite
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findDateSup($value1, $value2){
        return $this->createQueryBuilder('d')
            ->where('c.garage = :id')
            ->andWhere('c.DateRDV >= :date')
            ->setParameter('id', $value1)
            ->setParameter('date', $value2->format('Y-m-d'))
            ->getQuery()
            ->getResult();
    }
    public function findDate($value1,$value2){
        return $this->createQueryBuilder('d')
            ->where('d.garage = :id')
            ->andWhere('d.DateRDV = :date')
            ->setParameter('id', $value1)
            ->setParameter('date', $value2)
            ->getQuery()
            ->getResult();
    }
}
