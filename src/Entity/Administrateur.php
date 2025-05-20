<?php

namespace App\Entity;

use App\Repository\AdministrateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: AdministrateurRepository::class)]
class Administrateur implements UserInterface, PasswordAuthenticatedUserInterface
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

    /**
     * @var Collection<int, Employe>
     */
    #[ORM\OneToMany(targetEntity: Employe::class, mappedBy: 'creePar')]
    private Collection $employesCrees;

    public function __construct()
    {
        $this->employesCrees = new ArrayCollection();
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

    /**
     * @return Collection<int, Employe>
     */
    public function getEmployesCrees(): Collection
    {
        return $this->employesCrees;
    }

    public function addEmployesCree(Employe $employesCree): static
    {
        if (!$this->employesCrees->contains($employesCree)) {
            $this->employesCrees->add($employesCree);
            $employesCree->setCreePar($this);
        }

        return $this;
    }

    public function removeEmployesCree(Employe $employesCree): static
    {
        if ($this->employesCrees->removeElement($employesCree)) {
            if ($employesCree->getCreePar() === $this) {
                $employesCree->setCreePar(null);
            }
        }

        return $this;
    }

    // Implémentation des interfaces de sécurité

    public function getRoles(): array
    {
        return ['ROLE_ADMIN'];
    }

    public function getUserIdentifier(): string
    {
        return $this->email; // Utilisé comme identifiant de connexion
    }

    public function getPassword(): string
    {
        return $this->motDePasse;
    }

    public function eraseCredentials(): void
    {
        // Pas de données sensibles à nettoyer pour l’instant
    }
}
