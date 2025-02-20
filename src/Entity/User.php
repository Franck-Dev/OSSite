<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\ManyToMany(targetEntity: Site::class, inversedBy: 'users', fetch: 'EAGER')]
    private Collection $Site;

    #[ORM\ManyToOne(inversedBy: 'users', fetch: 'EAGER')]
    private ?Division $Division = null;

    #[ORM\ManyToMany(targetEntity: Mandat::class, inversedBy: 'users', fetch: 'EAGER')]
    private Collection $mandat;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(length: 255)]
    private ?string $token = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $dateAdhesion = null;

    #[ORM\Column(nullable: true)]
    private ?int $tel = null;

    #[ORM\OneToMany(targetEntity: Medias::class, mappedBy: 'createdBy')]
    private Collection $medias;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Statut $autorisation = null;

    #[ORM\OneToMany(targetEntity: Questions::class, mappedBy: 'demandeur')]
    private Collection $questions;

    #[ORM\OneToMany(targetEntity: Questions::class, mappedBy: 'repondeur')]
    private Collection $reponses;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $resetToken = null;

    #[ORM\OneToMany(targetEntity: Commentaires::class, mappedBy: 'createdBy')]
    private Collection $commentaires;

    public function __construct()
    {
        $this->Site = new ArrayCollection();
        $this->mandat = new ArrayCollection();
        $this->medias = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->reponses = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
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
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
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
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Site>
     */
    public function getSite(): Collection
    {
        return $this->Site;
    }

    public function addSite(Site $site): static
    {
        if (!$this->Site->contains($site)) {
            $this->Site->add($site);
        }

        return $this;
    }

    public function removeSite(Site $site): static
    {
        $this->Site->removeElement($site);

        return $this;
    }

    public function getDivision(): ?Division
    {
        return $this->Division;
    }

    public function setDivision(?Division $Division): static
    {
        $this->Division = $Division;

        return $this;
    }

    /**
     * @return Collection<int, Mandat>
     */
    public function getMandat(): Collection
    {
        return $this->mandat;
    }

    public function addMandat(Mandat $mandat): static
    {
        if (!$this->mandat->contains($mandat)) {
            $this->mandat->add($mandat);
        }

        return $this;
    }

    public function removeMandat(Mandat $mandat): static
    {
        $this->mandat->removeElement($mandat);

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateAdhesion(): ?\DateTimeImmutable
    {
        return $this->dateAdhesion;
    }

    public function setDateAdhesion(?\DateTimeImmutable $dateAdhesion): static
    {
        $this->dateAdhesion = $dateAdhesion;

        return $this;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(?int $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * @return Collection<int, Medias>
     */
    public function getMedias(): Collection
    {
        return $this->medias;
    }

    public function addMedia(Medias $media): static
    {
        if (!$this->medias->contains($media)) {
            $this->medias->add($media);
            $media->setCreatedBy($this);
        }

        return $this;
    }

    public function removeMedia(Medias $media): static
    {
        if ($this->medias->removeElement($media)) {
            // set the owning side to null (unless already changed)
            if ($media->getCreatedBy() === $this) {
                $media->setCreatedBy(null);
            }
        }

        return $this;
    }

    public function getAutorisation(): ?Statut
    {
        return $this->autorisation;
    }

    public function setAutorisation(?Statut $autorisation): static
    {
        $this->autorisation = $autorisation;

        return $this;
    }

    /**
     * @return Collection<int, Questions>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Questions $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setDemandeur($this);
        }

        return $this;
    }

    public function removeQuestion(Questions $question): static
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getDemandeur() === $this) {
                $question->setDemandeur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Questions>
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Questions $reponse): static
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses->add($reponse);
            $reponse->setRepondeur($this);
        }

        return $this;
    }

    public function removeReponse(Questions $reponse): static
    {
        if ($this->reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getRepondeur() === $this) {
                $reponse->setRepondeur(null);
            }
        }

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): static
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    /**
     * @return Collection<int, Commentaires>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaires $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getCreatedBy() === $this) {
                $commentaire->setCreatedBy(null);
            }
        }

        return $this;
    }
}
