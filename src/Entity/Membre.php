<?php

namespace App\Entity;

use App\Repository\MembreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MembreRepository::class)]
class Membre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 190, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 190, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 190, nullable: true)]
    private ?string $metier = null;

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

    public function getMetier(): ?string
    {
        return $this->metier;
    }

    public function setMetier(?string $metier): static
    {
        $this->metier = $metier;

        return $this;
    }
}
