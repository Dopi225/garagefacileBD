<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"AUTO")]
    #[ORM\Column(type:"integer")]
    private $idService;


    

      #[ORM\Column(type:"string", length:50)]

     

    private $libelleService;


    

      #[ORM\Column(type:"text")]

     

    private $descriptionService;


    public function getId(): ?int
    {
        return $this->idService;
    }

    public function getIdService(): ?int
    {
        return $this->idService;
    }

    public function getLibelleService(): ?string
    {
        return $this->libelleService;
    }

    public function setLibelleService(string $libelleService): static
    {
        $this->libelleService = $libelleService;

        return $this;
    }

    public function getDescriptionService(): ?string
    {
        return $this->descriptionService;
    }

    public function setDescriptionService(string $descriptionService): static
    {
        $this->descriptionService = $descriptionService;

        return $this;
    }
}
