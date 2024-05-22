<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ORM\Table(name: "Client")]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"AUTO")]
    #[ORM\Column(type:"integer")]
    private $idClient;


    

      #[ORM\Column(type:"string", length:50)]

     

    private $nomClient;


    

      #[ORM\Column(type:"string", length:50)]

     

    private $prenomClient;


    

      #[ORM\Column(type:"string", length:20, unique:true)]

     

    private $telephoneClient;


    

      #[ORM\Column(type:"string", length:100, unique:true)]

     

    private $emailClient;


    

      #[ORM\Column(type:"string", length:255, unique:true)]

     

    private $mdpClient;




      #[ORM\Column(type:"boolean")]

     

    private $bloquer = false;

    public function getId(): ?int
    {
        return $this->idClient;
    }

    public function getIdClient(): ?int
    {
        return $this->idClient;
    }

    public function getNomClient(): ?string
    {
        return $this->nomClient;
    }

    public function setNomClient(string $nomClient): static
    {
        $this->nomClient = $nomClient;

        return $this;
    }

    public function getPrenomClient(): ?string
    {
        return $this->prenomClient;
    }

    public function setPrenomClient(string $prenomClient): static
    {
        $this->prenomClient = $prenomClient;

        return $this;
    }

    public function getTelephoneClient(): ?string
    {
        return $this->telephoneClient;
    }

    public function setTelephoneClient(string $telephoneClient): static
    {
        $this->telephoneClient = $telephoneClient;

        return $this;
    }

    public function getEmailClient(): ?string
    {
        return $this->emailClient;
    }

    public function setEmailClient(string $emailClient): static
    {
        $this->emailClient = $emailClient;

        return $this;
    }

    public function getMdpClient(): ?string
    {
        return $this->mdpClient;
    }

    public function setMdpClient(string $mdpClient): static
    {
        $this->mdpClient = $mdpClient;

        return $this;
    }

    public function isBloquer(): ?bool
    {
        return $this->bloquer;
    }

    public function setBloquer(bool $bloquer): static
    {
        $this->bloquer = $bloquer;

        return $this;
    }
}
