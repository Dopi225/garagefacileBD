<?php

namespace App\Entity;

use App\Repository\VehiculeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
class Vehicule
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"AUTO")]
    #[ORM\Column(type:"integer")]
    private ?int $idVehicule;


    

      #[ORM\Column(type:"string", length:10)]

     

    private $immatriculation;

    #[ORM\Column(type:"bigint")]
    private  $kilometrage;


    

      #[ORM\ManyToOne(targetEntity:"App\Entity\MarqueVehicule")]
      #[ORM\JoinColumn(name:"idMarque", referencedColumnName:"id_marque",nullable:false)]

     

    private $marqueVehicule;


    

     // Relation avec Client
#[ORM\ManyToOne(targetEntity:"App\Entity\Client")]
#[ORM\JoinColumn(name:"idClient", referencedColumnName:"id_client",nullable:false)]
private $client;


    public function getId(): ?int
    {
        return $this->idVehicule;
    }

    public function getIdVehicule(): ?int
    {
        return $this->idVehicule;
    }

    public function getImmatriculation(): ?string
    {
        return $this->immatriculation;
    }

    public function setImmatriculation(string $immatriculation): static
    {
        $this->immatriculation = $immatriculation;

        return $this;
    }

    public function getMarqueVehicule(): ?MarqueVehicule
    {
        return $this->marqueVehicule;
    }

    public function setMarqueVehicule(?MarqueVehicule $marqueVehicule): static
    {
        $this->marqueVehicule = $marqueVehicule;

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

    public function getKilometrage()
    {
        return $this->kilometrage;
    }

    
    public function setKilometrage($kilometrage)
    {
        $this->kilometrage = $kilometrage;

        return $this;
    }
}
