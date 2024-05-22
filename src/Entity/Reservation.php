<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"AUTO")]
    #[ORM\Column(type:"integer")]
    private ?int $idReserv;

    
    #[ORM\Column(type:"date")]
   
  private $dateRDV;

  
    #[ORM\Column(type:"time")]
   
  private $heureRDV;

  
    #[ORM\Column(type:"text",nullable:true)]
   
  private $remarque;


  // Relation avec Garage
#[ORM\ManyToOne(targetEntity:"App\Entity\Garage")]
#[ORM\JoinColumn(name:"idGarage", referencedColumnName:"id_garage",nullable:false)]
private $garage;

  
  #[ORM\ManyToOne(targetEntity:"App\Entity\Client")]
  #[ORM\JoinColumn(name:"idClient", referencedColumnName:"id_client",nullable:false)]
  private $client; 

  
   // Relation avec statut
#[ORM\ManyToOne(targetEntity:"App\Entity\Statut")]
#[ORM\JoinColumn(name:"idStatut", referencedColumnName:"id_statut",nullable:false)]
private $statut ;

  
   // Relation avec Service
#[ORM\ManyToOne(targetEntity:"App\Entity\Service")]
#[ORM\JoinColumn(name:"idService", referencedColumnName:"id_service",nullable:false)]
private $service;

#[ORM\ManyToOne(targetEntity:"App\Entity\Vehicule")]
#[ORM\JoinColumn(name:"idVehicule", referencedColumnName:"id_vehicule",nullable:false)]
private $vehiculeClient;
   

    public function getId(): ?int
    {
        return $this->idReserv;
    }

    public function getIdReserv(): ?int
    {
        return $this->idReserv;
    }

    public function getDateRDV(): ?\DateTimeInterface
    {
        return $this->dateRDV;
    }

    public function setDateRDV(\DateTimeInterface $dateRDV): static
    {
        $this->dateRDV = $dateRDV;

        return $this;
    }

    public function getHeureRDV(): ?\DateTimeInterface
    {
        return $this->heureRDV;
    }

    public function setHeureRDV(\DateTimeInterface $heureRDV): static
    {
        $this->heureRDV = $heureRDV;

        return $this;
    }

    public function getRemarque(): ?string
    {
        return $this->remarque;
    }

    public function setRemarque(string $remarque): static
    {
        $this->remarque = $remarque;

        return $this;
    }


    public function getGarage(): ?Garage
    {
        return $this->garage;
    }

    public function setGarage(?Garage $garage): static
    {
        $this->garage = $garage;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getStatut(): ?Statut
    {
        return $this->statut;
    }

    public function setStatut(?Statut $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

  /**
   * Get the value of vehiculeClient
   */ 
  public function getVehiculeClient() : ?Vehicule
  {
    return $this->vehiculeClient;
  }

  /**
   * Set the value of vehiculeClient
   *
   * @return  self
   */ 
  public function setVehiculeClient(?Vehicule $vehiculeClient)
  {
    $this->vehiculeClient = $vehiculeClient;

    return $this;
  }

/**
 * Get the value of service
 */ 
public function getService()
{
return $this->service;
}

/**
 * Set the value of service
 *
 * @return  self
 */ 
public function setService($service)
{
$this->service = $service;

return $this;
}
}
