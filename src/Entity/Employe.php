<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: EmployeRepository::class)]
class Employe implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $motDePasse = null;

    #[ORM\Column(type: 'boolean')]
    private bool $actif = true;

    #[ORM\OneToMany(targetEntity: Avis::class, mappedBy: 'gerePar')]
    private Collection $avisGere;

    #[ORM\OneToMany(targetEntity: MessageContact::class, mappedBy: 'traitePar')]
    private Collection $messageContacts;

    #[ORM\ManyToOne(inversedBy: 'employesCrees')]
    private ?Administrateur $creePar = null;

    public function __construct()
    {
        $this->avisGere = new ArrayCollection();
        $this->messageContacts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
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

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): static
    {
        $this->motDePasse = $motDePasse;
        return $this;
    }

    public function isActif(): bool
    {
        return $this->actif;
    }

    public function getActif(): bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): static
    {
        $this->actif = $actif;
        return $this;
    }

    // === Obligatoire pour UserInterface ===

    public function getRoles(): array
    {
        return ['ROLE_EMPLOYE'];
    }

    public function getPassword(): string
    {
        return $this->motDePasse;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        // Rien à faire ici pour l'instant
    }

    public function getAvisGere(): Collection
    {
        return $this->avisGere;
    }

    public function addAvisGere(Avis $avisGere): static
    {
        if (!$this->avisGere->contains($avisGere)) {
            $this->avisGere->add($avisGere);
            $avisGere->setGerePar($this);
        }
        return $this;
    }

    public function removeAvisGere(Avis $avisGere): static
    {
        if ($this->avisGere->removeElement($avisGere)) {
            if ($avisGere->getGerePar() === $this) {
                $avisGere->setGerePar(null);
            }
        }
        return $this;
    }

    public function getMessageContacts(): Collection
    {
        return $this->messageContacts;
    }

    public function addMessageContact(MessageContact $messageContact): static
    {
        if (!$this->messageContacts->contains($messageContact)) {
            $this->messageContacts->add($messageContact);
            $messageContact->setTraitePar($this);
        }
        return $this;
    }

    public function removeMessageContact(MessageContact $messageContact): static
    {
        if ($this->messageContacts->removeElement($messageContact)) {
            if ($messageContact->getTraitePar() === $this) {
                $messageContact->setTraitePar(null);
            }
        }
        return $this;
    }

    public function getCreePar(): ?Administrateur
    {
        return $this->creePar;
    }

    public function setCreePar(?Administrateur $creePar): static
    {
        $this->creePar = $creePar;
        return $this;
    }
}