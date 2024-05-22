<?php

namespace App\Entity;

use App\Repository\PrestationGarageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrestationGarageRepository::class)]
class PrestationGarage
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"AUTO")]
    #[ORM\Column(type:"integer")]
    private ?int $idPrestation;


    

      #[ORM\Column(type:"integer")]

     

    private $tarifMinimumHT;


    

      #[ORM\Column(type:"integer")]

     

    private $dureeType;


    
   // Relation avec Service
   #[ORM\ManyToOne(targetEntity:"App\Entity\Service")]
   #[ORM\JoinColumn(name:"idService", referencedColumnName:"id_service",nullable:false)]
   private $service;

// Relation avec Garage
#[ORM\ManyToOne(targetEntity:"App\Entity\Garage")]
#[ORM\JoinColumn(name:"idGarage", referencedColumnName:"id_garage",nullable:false)]
private $garage;



    public function getIdPrestation(): ?int
    {
        return $this->idPrestation;
    }

    public function getTarifMinimumHT(): ?int
    {
        return $this->tarifMinimumHT;
    }

    public function setTarifMinimumHT(int $tarifMinimumHT): static
    {
        $this->tarifMinimumHT = $tarifMinimumHT;

        return $this;
    }

    public function getDureeType(): ?int
    {
        return $this->dureeType;
    }

    public function setDureeType(int $dureeType): static
    {
        $this->dureeType = $dureeType;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): static
    {
        $this->service = $service;

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
}
 