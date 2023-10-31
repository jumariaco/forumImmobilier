<?php

namespace App\Entity;

use App\Repository\PartenaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

#[Gedmo\SoftDeleteable(fieldName:"deletedAt", timeAware:false, hardDelete:false)]
#[ORM\Entity(repositoryClass: PartenaireRepository::class)]
class Partenaire
{
    use TimestampableEntity;
    use SoftDeleteableEntity;
    
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

    #[ORM\ManyToOne(inversedBy: 'partenaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Titre $titre = null;

    #[ORM\ManyToMany(targetEntity: Domaine::class, inversedBy: 'partenaires')]
    private Collection $domaines;

    

    public function __construct()
    {
        $this->domaines = new ArrayCollection();
    }

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

    public function getTitre(): ?Titre
    {
        return $this->titre;
    }

    public function setTitre(?Titre $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * @return Collection<int, Domaine>
     */
    public function getDomaines(): Collection
    {
        return $this->domaines;
    }

    public function addDomaine(Domaine $domaine): static
    {
        if (!$this->domaines->contains($domaine)) {
            $this->domaines->add($domaine);
        }

        return $this;
    }

    public function removeDomaine(Domaine $domaine): static
    {
        $this->domaines->removeElement($domaine);

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }

}
