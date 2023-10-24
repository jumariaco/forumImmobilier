<?php

namespace App\Entity;

use App\Repository\PartenaireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartenaireRepository::class)]
class Partenaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 190, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 190, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 190)]
    private ?string $societe = null;

    #[ORM\Column]
    private ?int $siret = null;

    #[ORM\Column(length: 190)]
    private ?string $metier = null;

    #[ORM\Column(length: 190, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 190, nullable: true)]
    private ?string $emailContact = null;

    #[ORM\Column(nullable: true)]
    private ?int $departement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getSociete(): ?string
    {
        return $this->societe;
    }

    public function setSociete(string $societe): static
    {
        $this->societe = $societe;

        return $this;
    }

    public function getSiret(): ?int
    {
        return $this->siret;
    }

    public function setSiret(int $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    public function getMetier(): ?string
    {
        return $this->metier;
    }

    public function setMetier(string $metier): static
    {
        $this->metier = $metier;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmailContact(): ?string
    {
        return $this->emailContact;
    }

    public function setEmailContact(?string $emailContact): static
    {
        $this->emailContact = $emailContact;

        return $this;
    }

    public function getDepartement(): ?int
    {
        return $this->departement;
    }

    public function setDepartement(?int $departement): static
    {
        $this->departement = $departement;

        return $this;
    }
}
