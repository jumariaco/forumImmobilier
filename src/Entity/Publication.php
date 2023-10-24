<?php

namespace App\Entity;

use App\Repository\PublicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

#[Gedmo\SoftDeleteable(fieldName:"deletedAt", timeAware:false, hardDelete:false)]
#[ORM\Entity(repositoryClass: PublicationRepository::class)]
class Publication
{
    use TimestampableEntity;
    use SoftDeleteableEntity;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 190)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;

    #[ORM\Column]
    private ?bool $statut = null;

    #[ORM\Column(length: 190, nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;

    #[ORM\Column(length: 190, nullable: true)]
    private ?string $typeMime = null;

    #[ORM\ManyToMany(targetEntity: Domaine::class, inversedBy: 'publications')]
    private Collection $domaines;

    #[ORM\OneToMany(mappedBy: 'publication', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    #[ORM\OneToMany(mappedBy: 'publication', targetEntity: Like::class)]
    private Collection $likes;

    #[ORM\ManyToOne(inversedBy: 'publications')]
    private ?User $user = null;

    public function __construct()
    {
        $this->domaines = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function isStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): static
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function setImageSize(?int $imageSize): static
    {
        $this->imageSize = $imageSize;

        return $this;
    }

    public function getTypeMime(): ?string
    {
        return $this->typeMime;
    }

    public function setTypeMime(?string $typeMime): static
    {
        $this->typeMime = $typeMime;

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

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setPublication($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getPublication() === $this) {
                $commentaire->setPublication(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): static
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setPublication($this);
        }

        return $this;
    }

    public function removeLike(Like $like): static
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getPublication() === $this) {
                $like->setPublication(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
