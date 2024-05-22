<?php

namespace App\Entity;

use App\Repository\DisponibiliteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DisponibiliteRepository::class)]
#[ORM\Table(name: 'disponibilite')]
class Disponibilite
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy:"AUTO")]
    private $idDispo;

    #[ORM\Column(type:"date")]
    private $DateRDV;

    #[ORM\Column(type:"integer")]
    private $nombreRDV;

    #[ORM\Column(type:"time")]
    private $HeureDebut;

    #[ORM\Column(type:"time")]
    private $HeureFin;

    //duree entre rendez-vous
    #[ORM\Column(type:"integer")]
    private $DureeRDV;

    // Relation avec Garage
    #[ORM\ManyToOne(targetEntity:"App\Entity\Garage")]
    #[ORM\JoinColumn(name:"idGarage", referencedColumnName:"id_garage")]
    private $garage;

    // Les getters et setters vont ici
   

    /**
     * Get the value of idCalendrier
     */ 
    public function getIdCalendrier()
    {
        return $this->idDispo;
    }

    /**
     * Set the value of idCalendrier
     *
     * @return  self
     */ 
    public function setIdCalendrier($idCalendrier)
    {
        $this->idDispo = $idCalendrier;

        return $this;
    }

    /**
     * Get the value of DateRDV
     */ 
    public function getDateRDV()
    {
        return $this->DateRDV;
    }

    /**
     * Set the value of DateRDV
     *
     * @return  self
     */ 
    public function setDateRDV($DateRDV)
    {
        $this->DateRDV = $DateRDV;

        return $this;
    }

    /**
     * Get the value of nombreRDV
     */ 
    public function getNombreRDV()
    {
        return $this->nombreRDV;
    }

    /**
     * Set the value of nombreRDV
     *
     * @return  self
     */ 
    public function setNombreRDV($nombreRDV)
    {
        $this->nombreRDV = $nombreRDV;

        return $this;
    }

    /**
     * Get the value of HeureDebut
     */ 
    public function getHeureDebut()
    {
        return $this->HeureDebut;
    }

    /**
     * Set the value of HeureDebut
     *
     * @return  self
     */ 
    public function setHeureDebut($HeureDebut)
    {
        $this->HeureDebut = $HeureDebut;

        return $this;
    }

    /**
     * Get the value of HeureFin
     */ 
    public function getHeureFin()
    {
        return $this->HeureFin;
    }

    /**
     * Set the value of HeureFin
     *
     * @return  self
     */ 
    public function setHeureFin($HeureFin)
    {
        $this->HeureFin = $HeureFin;

        return $this;
    }

    

    /**
     * Get the value of DureeRDV
     */ 
    public function getDureeRDV()
    {
        return $this->DureeRDV;
    }

    /**
     * Set the value of DureeRDV
     *
     * @return  self
     */ 
    public function setDureeRDV($DureeRDV)
    {
        $this->DureeRDV = $DureeRDV;

        return $this;
    }

    

    /**
     * Get the value of garage
     */ 
    public function getGarage()
    {
        return $this->garage;
    }

    /**
     * Set the value of garage
     *
     * @return  self
     */ 
    public function setGarage($garage)
    {
        $this->garage = $garage;

        return $this;
    }

}


?>