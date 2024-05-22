<?php

namespace App\Entity;

use App\Repository\HoraireStandardRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HoraireStandardRepository::class)]
class HoraireStandard
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"AUTO")]
    #[ORM\Column(type:"integer")]
    private ?int $idHoraire;


    

      #[ORM\Column(type:"string", length:10)]

     

    private $jour;


    

      #[ORM\Column(type:"time")]

     

    private $ouverture;


    

      #[ORM\Column(type:"time")]

     

    private $fermerture;


    
// Relation avec Garage
#[ORM\ManyToOne(targetEntity:"App\Entity\Garage")]
#[ORM\JoinColumn(name:"idGarage", referencedColumnName:"id_garage",nullable:false)]
private $garage;


    public function getId(): ?int
    {
        return $this->idHoraire;
    }

    public function getIdHoraire(): ?int
    {
        return $this->idHoraire;
    }

    public function getJour(): ?string
    {
        return $this->jour;
    }

    public function setJour(string $jour): static
    {
        $this->jour = $jour;

        return $this;
    }

    public function getOuverture(): ?\DateTimeInterface
    {
        return $this->ouverture;
    }

    public function setOuverture(\DateTimeInterface $ouverture): static
    {
        $this->ouverture = $ouverture;

        return $this;
    }

    public function getFermerture(): ?\DateTimeInterface
    {
        return $this->fermerture;
    }

    public function setFermerture(\DateTimeInterface $fermerture): static
    {
        $this->fermerture = $fermerture;

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
