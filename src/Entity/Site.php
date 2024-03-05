<?php

namespace App\Entity;

use App\Repository\SiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SiteRepository::class)]
class Site
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(targetEntity: Convention::class, mappedBy: 'site')]
    private Collection $convention;

    #[ORM\ManyToMany(targetEntity: Division::class, inversedBy: 'sites')]
    private Collection $Division;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'Site')]
    private Collection $users;

    public function __construct()
    {
        $this->convention = new ArrayCollection();
        $this->Division = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Convention>
     */
    public function getConvention(): Collection
    {
        return $this->convention;
    }

    public function addConvention(Convention $convention): static
    {
        if (!$this->convention->contains($convention)) {
            $this->convention->add($convention);
            $convention->setSite($this);
        }

        return $this;
    }

    public function removeConvention(Convention $convention): static
    {
        if ($this->convention->removeElement($convention)) {
            // set the owning side to null (unless already changed)
            if ($convention->getSite() === $this) {
                $convention->setSite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Division>
     */
    public function getDivision(): Collection
    {
        return $this->Division;
    }

    public function addDivision(Division $division): static
    {
        if (!$this->Division->contains($division)) {
            $this->Division->add($division);
        }

        return $this;
    }

    public function removeDivision(Division $division): static
    {
        $this->Division->removeElement($division);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addSite($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeSite($this);
        }

        return $this;
    }
}
