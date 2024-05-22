<?php

namespace App\Entity;

use App\Repository\HoraireExceptionnelleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HoraireExceptionnelleRepository::class)]
class HoraireExceptionnelle
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"AUTO")]
    #[ORM\Column(type:"integer")]
    private ?int $idHoraireExceptionnel;


    

      #[ORM\Column(type:"date")]

     

    private $dateExceptionnel;


    

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
        return $this->idHoraireExceptionnel;
    }

    public function getIdHoraireExceptionnel(): ?int
    {
        return $this->idHoraireExceptionnel;
    }

    public function getDateExceptionnel(): ?\DateTimeInterface
    {
        return $this->dateExceptionnel;
    }

    public function setDateExceptionnel(\DateTimeInterface $dateExceptionnel): static
    {
        $this->dateExceptionnel = $dateExceptionnel;

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
