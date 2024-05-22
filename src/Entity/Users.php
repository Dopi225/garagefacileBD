<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"AUTO")]
    #[ORM\Column(type:"integer")]
    private ?int $idUsers;


    

      #[ORM\Column(type:"string", length:50)]

     

    private $nom;


    

      #[ORM\Column(type:"string", length:50)]

     

    private $prenom;


    

      #[ORM\Column(type:"string", length:50)]

     

    private $login;


    

      #[ORM\Column(type:"string", length:255)]

     

    private $mdp;


    

      #[ORM\Column(type:"boolean")]

     

    private $responsable = false;

// Relation avec Garage
#[ORM\ManyToOne(targetEntity:"App\Entity\Garage")]
#[ORM\JoinColumn(name:"idGarage", referencedColumnName:"id_garage",nullable:false)]
private $garage;
    

    public function getIdUsers(): ?int
    {
        return $this->idUsers;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): static
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function isResponsable(): ?bool
    {
        return $this->responsable;
    }

    public function setResponsable(bool $responsable): static
    {
        $this->responsable = $responsable;

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
