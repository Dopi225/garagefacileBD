<?php

namespace App\Entity;

use App\Repository\StatutRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatutRepository::class)]
#[ORM\Table(name: 'statut')]
class Statut
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"AUTO")]
    #[ORM\Column(type:"integer")]
    private ?int $idStatut;


    

      #[ORM\Column(type:"string", length:50)]

     

    private $libelleStatut;

    public function getId(): ?int
    {
        return $this->idStatut;
    }

    public function getIdStatut(): ?int
    {
        return $this->idStatut;
    }

    public function getLibelleStatut(): ?string
    {
        return $this->libelleStatut;
    }

    public function setLibelleStatut(string $libelleStatut): static
    {
        $this->libelleStatut = $libelleStatut;

        return $this;
    }
}
