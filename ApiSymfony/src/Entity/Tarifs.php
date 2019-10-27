<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\TarifsRepository")
 */
class Tarifs
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $borneInferieur;

    /**
     * @ORM\Column(type="integer")
     */
    private $borneSuperieur;

    /**
     * @ORM\Column(type="integer")
     */
    private $valeur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBorneInferieur(): ?int
    {
        return $this->borneInferieur;
    }

    public function setBorneInferieur(int $borneInferieur): self
    {
        $this->borneInferieur = $borneInferieur;

        return $this;
    }

    public function getBorneSuperieur(): ?int
    {
        return $this->borneSuperieur;
    }

    public function setBorneSuperieur(int $borneSuperieur): self
    {
        $this->borneSuperieur = $borneSuperieur;

        return $this;
    }

    public function getValeur(): ?int
    {
        return $this->valeur;
    }

    public function setValeur(int $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }

}
