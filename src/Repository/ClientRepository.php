<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 *
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    //    /**
    //     * @return Client[] Returns an array of Client objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Client
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findReservationsVenirByGarageId($idClient): array
    {
        $verif = 1;
        $date = new \DateTime();

        return $this->createQueryBuilder('r')
            ->select('r.DateRDV', 'r.HeureRDV', 's.libelleService', 'pg.tarifMinimumHT', 'g.nomEtablissement', 'g.emailEtablissement', 'g.telephoneEtablissement', 'g.Adresse', 'g.cp', 'g.ville', 'st.libelleStatut')
            ->from('App\Entity\Reservation', 'r')

            ->join('r.garage', 'g')
        
            ->join('r.client', 'c')
        
            ->join('r.statut', 'st')
        
            ->join('r.service', 's')
        
            ->leftJoin('App\Entity\PrestationGarage', 'pg', \Doctrine\ORM\Query\Expr\Join::WITH, 'pg.service = s.idService')
            ->andWhere('r.DateRDV >= :date')
            ->andWhere('r.client = :id')
            ->andWhere('r.statut = :verif')
            ->addOrderBy('r.DateRDV')
            ->setParameter('date', $date)
            ->setParameter('id', $idClient)
            ->setParameter('verif', $verif)
            ->getQuery()
            ->getResult();
    }
    public function findReservationsPasseByGarageId($idClient): array
    {
        $verif = 1;
        $date = new \DateTime();

        return $this->createQueryBuilder('r')
            ->select('r.DateRDV', 'r.HeureRDV', 's.libelleService', 'pg.tarifMinimumHT', 'g.nomEtablissement', 'g.emailEtablissement', 'g.telephoneEtablissement', 'g.Adresse', 'g.cp', 'g.ville', 'st.libelleStatut')
            ->from('App\Entity\Reservation', 'r')

            ->join('r.garage', 'g')
        
            ->join('r.client', 'c')
        
            ->join('r.statut', 'st')
        
            ->join('r.service', 's')
        
            ->leftJoin('App\Entity\PrestationGarage', 'pg', \Doctrine\ORM\Query\Expr\Join::WITH, 'pg.service = s.idService')
            ->andWhere('r.DateRDV < :date')
            ->andWhere('r.client = :id')
            ->andWhere('r.statut = :verif')
            ->addOrderBy('r.DateRDV')
            ->setParameter('date', $date)
            ->setParameter('id', $idClient)
            ->setParameter('verif', $verif)
            ->getQuery()
            ->getResult();
    }
}
