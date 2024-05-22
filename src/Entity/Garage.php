<?php

namespace App\Entity;

use App\Repository\GarageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GarageRepository::class)]
#[ORM\Table(name: 'garage')]
class Garage
{
   

     #[ORM\Id]
     #[ORM\GeneratedValue(strategy:"AUTO")]
     #[ORM\Column(type:"integer")]
    private $idGarage;
    

    #[ORM\Column(type:"string", length:100)]

     

     private ?string $nomEtablissement;

    #[ORM\Column(type:"string", length:50)]
 
      
 
     private ?string $nomResponsable;

    #[ORM\Column(type:"string", length:50)]
 
     
 
     private $prenomResponsable;

 
       #[ORM\Column(type:"string", length:100, unique:true)]

     private $emailEtablissement;

       #[ORM\Column(type:"string", length:100, unique:true)]
 
     private $emailResponsable;

       #[ORM\Column(type:"text")]
 
     private $adresse;

       #[ORM\Column(type:"integer")]
 
     private $cp;

       #[ORM\Column(type:"string", length:250)]

     private $ville;

       #[ORM\Column(type:"text")]
 
     private $descriptionEtablissement;

       #[ORM\Column(type:"string", length:20)]

     private $telephoneEtablissement;

       #[ORM\Column(type:"boolean")]

     private $verifier = false;

     #[ORM\Column(type:"bigint", length:14)]
 
     private $siret;

    public function getId(): ?int
    {
        return $this->idGarage;
    }

     /**
      * Get the value of nomEtablissement
      */ 
     public function getNomEtablissement()
     {
          return $this->nomEtablissement;
     }

     /**
      * Set the value of nomEtablissement
      *
      * @return  self
      */ 
     public function setNomEtablissement($nomEtablissement)
     {
          $this->nomEtablissement = $nomEtablissement;

          return $this;
     }

     public function getIdGarage(): ?int
     {
         return $this->idGarage;
     }

     public function getNomResponsable(): ?string
     {
         return $this->nomResponsable;
     }

     public function setNomResponsable(string $nomResponsable): static
     {
         $this->nomResponsable = $nomResponsable;

         return $this;
     }

     public function getPrenomResponsable(): ?string
     {
         return $this->prenomResponsable;
     }

     public function setPrenomResponsable(string $prenomResponsable): static
     {
         $this->prenomResponsable = $prenomResponsable;

         return $this;
     }

     public function getEmailEtablissement(): ?string
     {
         return $this->emailEtablissement;
     }

     public function setEmailEtablissement(string $emailEtablissement): static
     {
         $this->emailEtablissement = $emailEtablissement;

         return $this;
     }

     public function getEmailResponsable(): ?string
     {
         return $this->emailResponsable;
     }

     public function setEmailResponsable(string $emailResponsable): static
     {
         $this->emailResponsable = $emailResponsable;

         return $this;
     }

     public function getAdresse(): ?string
     {
         return $this->adresse;
     }

     public function setAdresse(string $adresse): static
     {
         $this->adresse = $adresse;

         return $this;
     }

     public function getCp(): ?int
     {
         return $this->cp;
     }

     public function setCp(int $cp): static
     {
         $this->cp = $cp;

         return $this;
     }

     public function getVille(): ?string
     {
         return $this->ville;
     }

     public function setVille(string $ville): static
     {
         $this->ville = $ville;

         return $this;
     }

     public function getDescriptionEtablissement(): ?string
     {
         return $this->descriptionEtablissement;
     }

     public function setDescriptionEtablissement(string $descriptionEtablissement): static
     {
         $this->descriptionEtablissement = $descriptionEtablissement;

         return $this;
     }

     public function getTelephoneEtablissement(): ?string
     {
         return $this->telephoneEtablissement;
     }

     public function setTelephoneEtablissement(string $telephoneEtablissement): static
     {
         $this->telephoneEtablissement = $telephoneEtablissement;

         return $this;
     }

     public function isVerifier(): ?bool
     {
         return $this->verifier;
     }

     public function setVerifier(bool $verifier): static
     {
         $this->verifier = $verifier;

         return $this;
     }
     public function getSiret()
     {
          return $this->siret;
     }
     public function setSiret($siret)
     {
          $this->siret = $siret;

          return $this;
     }
}
