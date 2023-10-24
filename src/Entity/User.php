<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

#[Gedmo\SoftDeleteable(fieldName:"deletedAt", timeAware:false, hardDelete:false)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableEntity;
    use SoftDeleteableEntity;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 190)]
    private ?string $Pseudo = null;

    #[ORM\Column]
    private ?bool $actif = null;

    #[ORM\Column]
    private ?bool $acceptationCgu = null;

    #[ORM\Column]
    private ?bool $newsletter = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Publication::class)]
    private Collection $publications;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Partenaire $partenaire = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Membre $membre = null;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'abonnes')]
    private Collection $abonnement;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'abonnement')]
    private Collection $abonnes;

    public function __construct()
    {
        $this->publications = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->abonnement = new ArrayCollection();
        $this->abonnes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->Pseudo;
    }

    public function setPseudo(string $Pseudo): static
    {
        $this->Pseudo = $Pseudo;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): static
    {
        $this->actif = $actif;

        return $this;
    }

    public function isAcceptationCgu(): ?bool
    {
        return $this->acceptationCgu;
    }

    public function setAcceptationCgu(bool $acceptationCgu): static
    {
        $this->acceptationCgu = $acceptationCgu;

        return $this;
    }

    public function isNewsletter(): ?bool
    {
        return $this->newsletter;
    }

    public function setNewsletter(bool $newsletter): static
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    /**
     * @return Collection<int, Publication>
     */
    public function getPublications(): Collection
    {
        return $this->publications;
    }

    public function addPublication(Publication $publication): static
    {
        if (!$this->publications->contains($publication)) {
            $this->publications->add($publication);
            $publication->setUser($this);
        }

        return $this;
    }

    public function removePublication(Publication $publication): static
    {
        if ($this->publications->removeElement($publication)) {
            // set the owning side to null (unless already changed)
            if ($publication->getUser() === $this) {
                $publication->setUser(null);
            }
        }

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
            $commentaire->setUser($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getUser() === $this) {
                $commentaire->setUser(null);
            }
        }

        return $this;
    }

    public function getPartenaire(): ?Partenaire
    {
        return $this->partenaire;
    }

    public function setPartenaire(?Partenaire $partenaire): static
    {
        $this->partenaire = $partenaire;

        return $this;
    }

    public function getMembre(): ?Membre
    {
        return $this->membre;
    }

    public function setMembre(?Membre $membre): static
    {
        $this->membre = $membre;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getAbonnement(): Collection
    {
        return $this->abonnement;
    }

    public function addAbonnement(self $abonnement): static
    {
        if (!$this->abonnement->contains($abonnement)) {
            $this->abonnement->add($abonnement);
        }

        return $this;
    }

    public function removeAbonnement(self $abonnement): static
    {
        $this->abonnement->removeElement($abonnement);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getAbonnes(): Collection
    {
        return $this->abonnes;
    }

    public function addAbonne(self $abonne): static
    {
        if (!$this->abonnes->contains($abonne)) {
            $this->abonnes->add($abonne);
            $abonne->addAbonnement($this);
        }

        return $this;
    }

    public function removeAbonne(self $abonne): static
    {
        if ($this->abonnes->removeElement($abonne)) {
            $abonne->removeAbonnement($this);
        }

        return $this;
    }
}
