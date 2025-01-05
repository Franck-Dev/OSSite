<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MediasRepository;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: MediasRepository::class)]
class Medias
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $preface = null;

    #[ORM\ManyToOne(inversedBy: 'medias')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeMedias $type = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable:true)]
    private ?\DateTimeImmutable $modifedAt = null;

    #[ORM\Column]
    private ?bool $isArchived = null;

    #[ORM\Column(length: 255)]
    private ?string $fichierPath = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'medias')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    #[ORM\ManyToOne(inversedBy: 'medias')]
    private ?User $modifedBy = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?File $fichier = null;

    #[ORM\ManyToOne(inversedBy: 'medias')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Statut $visibilite = null;

    #[ORM\Column(length: 255)]
    private ?string $perimetre = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPreface(): ?string
    {
        return $this->preface;
    }

    public function setPreface(?string $preface): static
    {
        $this->preface = $preface;

        return $this;
    }

    public function getType(): ?TypeMedias
    {
        return $this->type;
    }

    public function setType(?TypeMedias $type): static
    {
        $this->type = $type;

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

    public function getModifedAt(): ?\DateTimeImmutable
    {
        return $this->modifedAt;
    }

    public function setModifedAt(\DateTimeImmutable $modifedAt): static
    {
        $this->modifedAt = $modifedAt;

        return $this;
    }

    public function isIsArchived(): ?bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(bool $isArchived): static
    {
        $this->isArchived = $isArchived;

        return $this;
    }

    public function getFichierPath(): ?string
    {
        return $this->fichierPath;
    }

    public function setFichierPath(string $fichierPath): static
    {
        $this->fichierPath = $fichierPath;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getModifedBy(): ?User
    {
        return $this->modifedBy;
    }

    public function setModifedBy(?User $modifedBy): static
    {
        $this->modifedBy = $modifedBy;

        return $this;
    }

    public function getFichier(): ?File
    {
        return $this->fichier;
    }

    public function setFichier(?string $fichier): static
    {
        $this->fichier = $fichier;

        return $this;
    }

    public function getVisibilite(): ?Statut
    {
        return $this->visibilite;
    }

    public function setVisibilite(?Statut $visibilite): static
    {
        $this->visibilite = $visibilite;

        return $this;
    }

    public function getPerimetre(): ?string
    {
        return $this->perimetre;
    }

    public function setPerimetre(string $perimetre): static
    {
        $this->perimetre = $perimetre;

        return $this;
    }
}
