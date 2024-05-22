<?php

namespace App\Entity;

use App\Repository\ImageGarageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageGarageRepository::class)]
class ImageGarage
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"AUTO")]
    #[ORM\Column(type:"integer")]
    private ?int $idImage;


    

      #[ORM\Column(type:"string", length:255)]

     

    private $urlImage;


    

      #[ORM\Column(type:"text")]

     

    private $descriptionImage;

// Relation avec Garage
#[ORM\ManyToOne(targetEntity:"App\Entity\Garage")]
#[ORM\JoinColumn(name:"idGarage", referencedColumnName:"id_garage",nullable:false)]
private $garage;
    public function getId(): ?int
    {
        return $this->idImage;
    }

    public function getIdImage(): ?int
    {
        return $this->idImage;
    }

    public function getUrlImage(): ?string
    {
        return $this->urlImage;
    }

    public function setUrlImage(string $urlImage): static
    {
        $this->urlImage = $urlImage;

        return $this;
    }

    public function getDescriptionImage(): ?string
    {
        return $this->descriptionImage;
    }

    public function setDescriptionImage(string $descriptionImage): static
    {
        $this->descriptionImage = $descriptionImage;

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
