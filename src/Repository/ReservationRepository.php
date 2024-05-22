<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    //    /**
    //     * @return Reservation[] Returns an array of Reservation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Reservation
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findReservationsAttenteByClientId($idClient): array
{
    $verif = 1;
    $date = new \DateTime();
    $date->format('Y-m-d');

    return $this->createQueryBuilder('r')
        ->select('r.idReserv','r.dateRDV', 'r.remarque', 'r.heureRDV',  'c.nomClient',  'c.prenomClient',  'c.emailClient',  'c.telephoneClient',  'v.immatriculation', 'm.marque', 's.libelleService', 'pg.tarifMinimumHT', 'g.nomEtablissement', 'g.emailEtablissement', 'g.telephoneEtablissement', 'g.adresse', 'g.cp', 'g.ville', 'st.libelleStatut')
        ->join('r.garage', 'g')
    
        ->join('r.client', 'c')
    
        ->join('r.statut', 'st')
    
        ->join('r.service', 's')
        ->join('r.vehiculeClient', 'v')
    
        ->leftJoin('App\Entity\PrestationGarage', 'pg', \Doctrine\ORM\Query\Expr\Join::WITH, 'pg.service = s.idService')
        ->leftJoin('App\Entity\MarqueVehicule', 'm', \Doctrine\ORM\Query\Expr\Join::WITH, 'm.idMarque = v.marqueVehicule')
        ->andWhere('r.dateRDV >= :date')
        ->andWhere('c.idClient = :id') // Utilisation de l'alias correct pour le client
        ->andWhere('r.statut = :verif')
        ->addOrderBy('r.dateRDV')
        ->setParameter('date', $date->format('Y-m-d'))
        ->setParameter('id', $idClient)
        ->setParameter('verif', $verif)
        ->getQuery()
        ->getResult();
}
    public function findReservationsVenirByClientId($idClient): array
{
    $verif = 2;
    $date = new \DateTime();
    $date->format('Y-m-d');

    return $this->createQueryBuilder('r')
        ->select('r.idReserv','r.dateRDV', 'r.remarque', 'r.heureRDV',  'c.nomClient',  'c.prenomClient',  'c.emailClient',  'c.telephoneClient',  'v.immatriculation', 'm.marque', "s.idService" , 's.libelleService', 'pg.tarifMinimumHT', 'g.nomEtablissement', 'g.emailEtablissement', 'g.telephoneEtablissement', 'g.adresse', 'g.cp', 'g.ville', 'st.libelleStatut')
        ->join('r.garage', 'g')
    
        ->join('r.client', 'c')
    
        ->join('r.statut', 'st')
    
        ->join('r.service', 's')
        ->join('r.vehiculeClient', 'v')
    
        ->leftJoin('App\Entity\PrestationGarage', 'pg', \Doctrine\ORM\Query\Expr\Join::WITH, 'pg.service = s.idService')
        ->leftJoin('App\Entity\MarqueVehicule', 'm', \Doctrine\ORM\Query\Expr\Join::WITH, 'm.idMarque = v.marqueVehicule')
        ->andWhere('r.dateRDV >= :date')
        ->andWhere('c.idClient = :id') // Utilisation de l'alias correct pour le client
        ->andWhere('r.statut = :verif')
        ->addOrderBy('r.dateRDV')
        ->setParameter('date', $date->format('Y-m-d'))
        ->setParameter('id', $idClient)
        ->setParameter('verif', $verif)
        ->getQuery()
        ->getResult();
}
public function findReservationsPasseByClientId($idClient): array
{
    $verif = 2;
    $date = new \DateTime();

    return $this->createQueryBuilder('r')
        ->select('DISTINCT r.idReserv','r.dateRDV', 'r.remarque', 'r.heureRDV', 's.libelleService', 'pg.tarifMinimumHT',  'c.nomClient',  'c.prenomClient',  'c.emailClient',  'c.telephoneClient',  'v.immatriculation', 'm.marque', 'g.nomEtablissement', 'g.emailEtablissement', 'g.telephoneEtablissement', 'g.adresse', 'g.cp', 'g.ville', 'st.libelleStatut')
        ->join('r.garage', 'g')
    
        ->join('r.client', 'c')
    
        ->join('r.statut', 'st')
    
        ->join('r.service', 's')
        ->join('r.vehiculeClient', 'v')
        ->join('v.marqueVehicule', 'm')
        ->leftJoin('App\Entity\PrestationGarage', 'pg', \Doctrine\ORM\Query\Expr\Join::WITH, 'pg.service = s.idService')
        // ->leftJoin('App\Entity\MarqueVehicule', 'm', \Doctrine\ORM\Query\Expr\Join::WITH, 'm.idMarque = v.marqueVehicule')
        ->andWhere('r.dateRDV < :date')
        ->andWhere('c.idClient = :id') // Utilisation de l'alias correct pour le client
        ->andWhere('r.statut = :verif')
        ->addOrderBy('r.dateRDV')
        ->setParameter('date', $date->format('Y-m-d'))
        ->setParameter('id', $idClient)
        ->setParameter('verif', $verif)
        ->getQuery()
        ->getResult();
}
public function findReservationsVenirByGarageId($idGarage): array
{
    $verif = 2;
    $date = new \DateTime();

    return $this->createQueryBuilder('r')
        ->select('DISTINCT r.idReserv','r.remarque','r.dateRDV', 'r.heureRDV', 's.libelleService', 'pg.tarifMinimumHT',  'c.nomClient',  'c.prenomClient',  'c.emailClient',  'c.telephoneClient',  'v.immatriculation', 'm.marque',  'g.nomEtablissement', 'g.emailEtablissement', 'g.telephoneEtablissement', 'g.adresse', 'g.cp', 'g.ville', 'st.libelleStatut')
        ->join('r.garage', 'g')
    
        ->join('r.client', 'c')
    
        ->join('r.statut', 'st')
    
        ->join('r.service', 's')
        ->join('r.vehiculeClient', 'v')
        ->join('v.marqueVehicule', 'm')
        ->leftJoin('App\Entity\PrestationGarage', 'pg', \Doctrine\ORM\Query\Expr\Join::WITH, 'pg.service = s.idService')
        ->andWhere('r.dateRDV >= :date')
        ->andWhere('g.idGarage = :id') // Utilisation de l'alias correct pour le garage
        ->andWhere('r.statut = :verif')
        ->addOrderBy('r.dateRDV')
        ->setParameter('date', $date->format('Y-m-d'))
        ->setParameter('id', $idGarage)
        ->setParameter('verif', $verif)
        ->getQuery()
        ->getResult();
}
public function findReservationsPasseByGarageId($idGarage): array
{
    $attente = 1;
    $confirmee = 2;
    $date = new \DateTime();

    return $this->createQueryBuilder('r')
        ->select('DISTINCT r.idReserv','r.remarque','r.dateRDV', 'r.heureRDV', 's.libelleService', 'pg.tarifMinimumHT',  'c.nomClient',  'c.prenomClient',  'c.emailClient',  'c.telephoneClient',  'v.immatriculation', 'm.marque',  'g.nomEtablissement', 'g.emailEtablissement', 'g.telephoneEtablissement', 'g.adresse', 'g.cp', 'g.ville', 'st.libelleStatut')
        ->join('r.garage', 'g')
    
        ->join('r.client', 'c')
    
        ->join('r.statut', 'st')
    
        ->join('r.service', 's')
        ->join('r.vehiculeClient', 'v')
        ->join('v.marqueVehicule', 'm')
        ->leftJoin('App\Entity\PrestationGarage', 'pg', \Doctrine\ORM\Query\Expr\Join::WITH, 'pg.service = s.idService')
        ->Where('r.dateRDV <= :date')
        ->andWhere('g.idGarage = :id') // Utilisation de l'alias correct pour le garage
        ->andWhere('r.statut != :attente')
        ->orWhere('r.statut != :confirmee')
        ->addOrderBy('r.dateRDV')
        ->setParameter('date', $date->format('Y-m-d'))
        ->setParameter('id', $idGarage)
        ->setParameter('attente', $attente)
        ->setParameter('confirmee', $confirmee)
        ->getQuery()
        ->getResult();
}
public function findReservationsDate($idGarage,$date): array
{
    $verif = 2;
    $date = new \DateTime($date);

    return $this->createQueryBuilder('r')
    ->select('DISTINCT r.idReserv','r.remarque','r.dateRDV', 'r.heureRDV', 's.libelleService', 'pg.tarifMinimumHT',  'c.nomClient',  'c.prenomClient',  'c.emailClient',  'c.telephoneClient',  'v.immatriculation', 'm.marque',  'g.nomEtablissement', 'g.emailEtablissement', 'g.telephoneEtablissement', 'g.adresse', 'g.cp', 'g.ville', 'st.libelleStatut')
    ->join('r.garage', 'g')

    ->join('r.client', 'c')

    ->join('r.statut', 'st')

    ->join('r.service', 's')
    ->join('r.vehiculeClient', 'v')
    ->join('v.marqueVehicule', 'm')
    ->leftJoin('App\Entity\PrestationGarage', 'pg', \Doctrine\ORM\Query\Expr\Join::WITH, 'pg.service = s.idService')
    ->andWhere('r.dateRDV = :date')
    ->andWhere('g.idGarage = :id') // Utilisation de l'alias correct pour le garage
    ->andWhere('r.statut = :verif')
    ->addOrderBy('r.dateRDV')
    ->setParameter('date', $date->format('Y-m-d'))
    ->setParameter('id', $idGarage)
    ->setParameter('verif', $verif)
    ->getQuery()
    ->getResult();
}

}
