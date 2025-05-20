<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Entity\Vehicule;
use App\Entity\Preference;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $motDePasse = null;

    #[ORM\Column]
    private ?int $credits = 20;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\OneToMany(targetEntity: Covoiturage::class, mappedBy: 'chauffeur')]
    private Collection $covoiturages;

    #[ORM\ManyToMany(targetEntity: Covoiturage::class, mappedBy: 'passagers')]
    private Collection $participations;

    #[ORM\OneToOne(mappedBy: 'utilisateur', targetEntity: Preference::class, cascade: ['persist', 'remove'])]
    private ?Preference $preference = null;

    #[ORM\OneToMany(targetEntity: Avis::class, mappedBy: 'auteur')]
    private Collection $avisRediges;

    #[ORM\OneToMany(targetEntity: Avis::class, mappedBy: 'cible')]
    private Collection $avisRecus;

    #[ORM\OneToMany(mappedBy: 'proprietaire', targetEntity: Vehicule::class)]
    private Collection $vehicules;

    /**
     * @var Collection<int, Incident>
     */
    #[ORM\OneToMany(targetEntity: Incident::class, mappedBy: 'signalPar')]
    private Collection $incidents;

    public function __construct()
    {
        $this->roles = ['ROLE_USER'];
        $this->covoiturages = new ArrayCollection();
        $this->participations = new ArrayCollection();
        $this->avisRediges = new ArrayCollection();
        $this->avisRecus = new ArrayCollection();
        $this->vehicules = new ArrayCollection();
        $this->incidents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;
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

    public function getCredits(): ?int
    {
        return $this->credits;
    }

    public function setCredits(int $credits): static
    {
        $this->credits = $credits;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles ?? [];

        if (!in_array('ROLE_USER', $roles, true)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
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
        // Nettoyage des données sensibles si nécessaire
    }

    public function getCovoiturages(): Collection
    {
        return $this->covoiturages;
    }

    public function addCovoiturage(Covoiturage $covoiturage): static
    {
        if (!$this->covoiturages->contains($covoiturage)) {
            $this->covoiturages->add($covoiturage);
            $covoiturage->setChauffeur($this);
        }
        return $this;
    }

    public function removeCovoiturage(Covoiturage $covoiturage): static
    {
        if ($this->covoiturages->removeElement($covoiturage)) {
            if ($covoiturage->getChauffeur() === $this) {
                $covoiturage->setChauffeur(null);
            }
        }
        return $this;
    }

    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Covoiturage $participation): static
    {
        if (!$this->participations->contains($participation)) {
            $this->participations->add($participation);
            $participation->addPassager($this);
        }
        return $this;
    }

    public function removeParticipation(Covoiturage $participation): static
    {
        if ($this->participations->removeElement($participation)) {
            $participation->removePassager($this);
        }
        return $this;
    }

    public function getPreference(): ?Preference
    {
        return $this->preference;
    }

    public function setPreference(?Preference $preference): static
    {
        if ($preference && $preference->getUtilisateur() !== $this) {
            $preference->setUtilisateur($this);
        }

        $this->preference = $preference;
        return $this;
    }

    public function getAvisRediges(): Collection
    {
        return $this->avisRediges;
    }

    public function addAvisRedige(Avis $avisRedige): static
    {
        if (!$this->avisRediges->contains($avisRedige)) {
            $this->avisRediges->add($avisRedige);
            $avisRedige->setAuteur($this);
        }
        return $this;
    }

    public function removeAvisRedige(Avis $avisRedige): static
    {
        if ($this->avisRediges->removeElement($avisRedige)) {
            if ($avisRedige->getAuteur() === $this) {
                $avisRedige->setAuteur(null);
            }
        }
        return $this;
    }

    public function getAvisRecus(): Collection
    {
        return $this->avisRecus;
    }

    public function addAvisRecu(Avis $avisRecu): static
    {
        if (!$this->avisRecus->contains($avisRecu)) {
            $this->avisRecus->add($avisRecu);
            $avisRecu->setCible($this);
        }
        return $this;
    }

    public function removeAvisRecu(Avis $avisRecu): static
    {
        if ($this->avisRecus->removeElement($avisRecu)) {
            if ($avisRecu->getCible() === $this) {
                $avisRecu->setCible(null);
            }
        }
        return $this;
    }

    public function getVehicules(): Collection
    {
        return $this->vehicules;
    }

    public function addVehicule(Vehicule $vehicule): static
    {
        if (!$this->vehicules->contains($vehicule)) {
            $this->vehicules->add($vehicule);
            $vehicule->setProprietaire($this);
        }
        return $this;
    }

    public function removeVehicule(Vehicule $vehicule): static
    {
        if ($this->vehicules->removeElement($vehicule)) {
            if ($vehicule->getProprietaire() === $this) {
                $vehicule->setProprietaire(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Incident>
     */
    public function getIncidents(): Collection
    {
        return $this->incidents;
    }

    public function addIncident(Incident $incident): static
    {
        if (!$this->incidents->contains($incident)) {
            $this->incidents->add($incident);
            $incident->setSignalPar($this);
        }

        return $this;
    }

    public function removeIncident(Incident $incident): static
    {
        if ($this->incidents->removeElement($incident)) {
            // set the owning side to null (unless already changed)
            if ($incident->getSignalPar() === $this) {
                $incident->setSignalPar(null);
            }
        }

        return $this;
    }
}

