<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 */
class Transaction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="transaction")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="transaction")
     * @ORM\JoinColumn(nullable=false)
     */
    private $compte;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"show"})
    * @Groups({"show"})
     */
    private $montant;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"show"})
     */
    private $cometat;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"show"})
     */
    private $comsystem;

    /**
     * @ORM\Column(type="integer")
     */
    private $comenvoie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
    * @Groups({"show"})
     */
    private $frais;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"show"})
     */
    private $comretrait;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show"})
     */
    private $nomE;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show"})
     */
    private $prenomE;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telE;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"show"})
     */
    private $dateEnvoie;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show"})
     */
    private $nomEx;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show"})
     */
    private $prenomEx;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresseEx;

    /**
     * @ORM\Column(type="integer")
     */
    private $telephoneEx;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cniEx;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"show"})
     */
    private $dateRetrait;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    public function setCompte(?Compte $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getTarifs(): ?Tarifs
    {
        return $this->tarifs;
    }

    public function setTarifs(?Tarifs $tarifs): self
    {
        $this->tarifs = $tarifs;

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

    public function getCometat(): ?int
    {
        return $this->cometat;
    }

    public function setCometat(int $cometat): self
    {
        $this->cometat = $cometat;

        return $this;
    }

    public function getComsystem(): ?int
    {
        return $this->comsystem;
    }

    public function setComsystem(int $comsystem): self
    {
        $this->comsystem = $comsystem;

        return $this;
    }

    public function getComenvoie(): ?int
    {
        return $this->comenvoie;
    }

    public function setComenvoie(int $comenvoie): self
    {
        $this->comenvoie = $comenvoie;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getFrais(): ?int
    {
        return $this->frais;
    }

    public function setFrais(int $frais): self
    {
        $this->frais = $frais;

        return $this;
    }

    public function getComretrait(): ?int
    {
        return $this->comretrait;
    }

    public function setComretrait(int $comretrait): self
    {
        $this->comretrait = $comretrait;

        return $this;
    }

    public function getNomE(): ?string
    {
        return $this->nomE;
    }

    public function setNomE(string $nomE): self
    {
        $this->nomE = $nomE;

        return $this;
    }

    public function getPrenomE(): ?string
    {
        return $this->prenomE;
    }

    public function setPrenomE(string $prenomE): self
    {
        $this->prenomE = $prenomE;

        return $this;
    }

    public function getTelE(): ?string
    {
        return $this->telE;
    }

    public function setTelE(string $telE): self
    {
        $this->telE = $telE;

        return $this;
    }

    public function getDateEnvoie(): ?\DateTimeInterface
    {
        return $this->dateEnvoie;
    }

    public function setDateEnvoie(\DateTimeInterface $dateEnvoie): self
    {
        $this->dateEnvoie = $dateEnvoie;

        return $this;
    }

    public function getNomEx(): ?string
    {
        return $this->nomEx;
    }

    public function setNomEx(string $nomEx): self
    {
        $this->nomEx = $nomEx;

        return $this;
    }

    public function getPrenomEx(): ?string
    {
        return $this->prenomEx;
    }

    public function setPrenomEx(string $prenomEx): self
    {
        $this->prenomEx = $prenomEx;

        return $this;
    }

    public function getAdresseEx(): ?string
    {
        return $this->adresseEx;
    }

    public function setAdresseEx(string $adresseEx): self
    {
        $this->adresseEx = $adresseEx;

        return $this;
    }

    public function getTelephoneEx(): ?int
    {
        return $this->telephoneEx;
    }

    public function setTelephoneEx(int $telephoneEx): self
    {
        $this->telephoneEx = $telephoneEx;

        return $this;
    }

    public function getCniEx(): ?int
    {
        return $this->cniEx;
    }

    public function setCniEx(int $cniEx): self
    {
        $this->cniEx = $cniEx;

        return $this;
    }

    public function getDateRetrait(): ?\DateTimeInterface
    {
        return $this->dateRetrait;
    }

    public function setDateRetrait(?\DateTimeInterface $dateRetrait): self
    {
        $this->dateRetrait = $dateRetrait;

        return $this;
    }
}
