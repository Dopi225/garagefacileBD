<?php

namespace App\Repository;

use App\Entity\PrestationGarage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PrestationGarage>
 *
 * @method PrestatonGarage|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrestatonGarage|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrestatonGarage[]    findAll()
 * @method PrestatonGarage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrestationGarageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrestationGarage::class);
    }

    //    /**
    //     * @return PrestatonGarage[] Returns an array of PrestatonGarage objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?PrestatonGarage
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    //value =idService
    public function findGarage($value){
        return $this->createQueryBuilder('pg')
        ->select('pg.idPrestation', 's.libelleService', 's.descriptionService', 'pg.Tarif', 'g.idGarage', 'g.NomEtablissement', 'g.NomResponsable', 'g.PrenomResponsable', 'g.EmailEtablissement', 'g.Adresse', 'g.CP', 'g.Ville', 'g.DescriptionEtablissement', 'g.TelephoneEtablissement')
        ->join('pg.service', 's')
                
        ->join('pg.garage', 'g')

        ->where('pg.service = :id')
        ->setParameter('id', $value)
        ->getQuery()
        ->getResult();

    }

    //value1=ville, value2=idService, value3=date 
    public function findGarageDate($value1, $value2, $value3, int $verif = 1)
    {
        return $this->createQueryBuilder('pg')
        ->select( 'g.idGarage','g.nomEtablissement','g.descriptionEtablissement', 'g.telephoneEtablissement', 'g.adresse', 'g.cp', 'g.ville','i.urlImage', 'pg.tarifMinimumHT', 'pg.dureeType','s.libelleService', 's.descriptionService',)
        ->join('pg.service', 's')
        ->join('pg.garage', 'g')
        ->leftJoin('App\Entity\Disponibilite', 'd', \Doctrine\ORM\Query\Expr\Join::WITH, 'd.garage = g.idGarage' )
        ->leftJoin('App\Entity\ImageGarage', 'i', \Doctrine\ORM\Query\Expr\Join::WITH, 'i.garage = g.idGarage' )
        ->where('g.ville = :ville')
        ->andwhere('s.idService = :id')
        ->andwhere('g.verifier = :verif')
        ->andWhere('d.DateRDV >= :date')
        ->setParameter('ville', $value1)
        ->setParameter('verif', $verif)
        ->setParameter('id', $value2)
        ->setParameter('date', $value3)
        ->getQuery()
        ->getResult();
    }
}
