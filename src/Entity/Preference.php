<?php

namespace App\Entity;

use App\Repository\PreferenceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PreferenceRepository::class)]
class Preference
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $fumeur = null;

    #[ORM\Column]
    private ?bool $animaux = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $autresPreferences = null;

    #[ORM\ManyToOne(inversedBy: 'preferences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $utilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isFumeur(): ?bool
    {
        return $this->fumeur;
    }

    public function setFumeur(bool $fumeur): static
    {
        $this->fumeur = $fumeur;

        return $this;
    }

    public function isAnimaux(): ?bool
    {
        return $this->animaux;
    }

    public function setAnimaux(bool $animaux): static
    {
        $this->animaux = $animaux;

        return $this;
    }

    public function getAutresPreferences(): ?string
    {
        return $this->autresPreferences;
    }

    public function setAutresPreferences(?string $autresPreferences): static
    {
        $this->autresPreferences = $autresPreferences;

        return $this;
    }

    public function getUtilisateur(): ?User
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?User $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}
