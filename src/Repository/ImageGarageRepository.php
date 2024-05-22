<?php

namespace App\Repository;

use App\Entity\ImageGarage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImageGarage>
 *
 * @method ImageGarage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageGarage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageGarage[]    findAll()
 * @method ImageGarage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageGarageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageGarage::class);
    }

    //    /**
    //     * @return ImageGarage[] Returns an array of ImageGarage objects
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

    //    public function findOneBySomeField($value): ?ImageGarage
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
