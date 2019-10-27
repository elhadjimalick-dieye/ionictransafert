<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\DepotRepository")
 */
class Depot
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"list", "show"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide")
     * @Assert\DateTime()
     * @var string A "Y-m-d H:i:s" formatted value
     * @Groups({"list", "show"})
     */
    private $datedepot;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Ce champ ne doit pas être vide")
     * @Assert\Range(min="75000", minMessage="Le depot doit etre superieur à {{ limit }}",)
     * @Groups({"list", "show"})
     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="depot")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"list", "show"})
     */
    private $compte;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="depot")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"list", "show"})
     */
    private $user;

    /* public function __construct()
    {
        $this->user = new ArrayCollection();
    } */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatedepot(): ?\DateTimeInterface
    {
        return $this->datedepot;
    }

    public function setDatedepot(\DateTimeInterface $datedepot): self
    {
        $this->datedepot = $datedepot;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    public function setCompte(?Compte $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
