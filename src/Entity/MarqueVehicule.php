<?php

namespace App\Entity;

use App\Repository\MarqueVehiculeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MarqueVehiculeRepository::class)]
class MarqueVehicule
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"AUTO")]
    #[ORM\Column(type:"integer")]
    private ?int $idMarque;


    

      #[ORM\Column(type:"string", length:50)]

     

    private $marque;


    public function getId(): ?int
    {
        return $this->idMarque;
    }

    public function getIdMarque(): ?int
    {
        return $this->idMarque;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): static
    {
        $this->marque = $marque;

        return $this;
    }
}
