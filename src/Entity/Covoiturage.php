<?php

namespace App\Entity;

use App\Repository\CovoiturageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CovoiturageRepository::class)]
class Covoiturage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $depart = null;

    #[ORM\Column(length: 255)]
    private ?string $arrivee = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDepart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateArrivee = null;

    #[ORM\Column]
    private ?int $nbPlacesDispo = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column]
    private ?bool $estEcologique = null;

    #[ORM\ManyToOne(inversedBy: 'covoiturages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $chauffeur = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'participations')]
    private Collection $passagers;

    #[ORM\ManyToOne(inversedBy: 'covoiturages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Vehicule $vehicule = null;

    #[ORM\Column(length: 20)]
    private ?string $etat = 'prévu';

    /**
     * @var Collection<int, User>
     */
   #[ORM\ManyToMany(targetEntity: User::class)]
    #[ORM\JoinTable(name: 'covoiturage_validations')]
    private Collection $passagersValidations;

    public function __construct()
    {
        $this->passagers = new ArrayCollection();
        $this->passagersValidations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepart(): ?string
    {
        return $this->depart;
    }

    public function setDepart(string $depart): static
    {
        $this->depart = $depart;

        return $this;
    }

    public function getArrivee(): ?string
    {
        return $this->arrivee;
    }

    public function setArrivee(string $arrivee): static
    {
        $this->arrivee = $arrivee;

        return $this;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->dateDepart;
    }

    public function setDateDepart(\DateTimeInterface $dateDepart): static
    {
        $this->dateDepart = $dateDepart;

        return $this;
    }

    public function getDateArrivee(): ?\DateTimeInterface
    {
        return $this->dateArrivee;
    }

    public function setDateArrivee(\DateTimeInterface $dateArrivee): static
    {
        $this->dateArrivee = $dateArrivee;

        return $this;
    }

    public function getNbPlacesDispo(): ?int
    {
        return $this->nbPlacesDispo;
    }

    public function setNbPlacesDispo(int $nbPlacesDispo): static
    {
        $this->nbPlacesDispo = $nbPlacesDispo;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function isEstEcologique(): ?bool
    {
        return $this->estEcologique;
    }

    public function setEstEcologique(bool $estEcologique): static
    {
        $this->estEcologique = $estEcologique;

        return $this;
    }

    public function getChauffeur(): ?User
    {
        return $this->chauffeur;
    }

    public function setChauffeur(?User $chauffeur): static
    {
        $this->chauffeur = $chauffeur;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getPassagers(): Collection
    {
        return $this->passagers;
    }

    public function addPassager(User $passager): static
    {
        if (!$this->passagers->contains($passager)) {
            $this->passagers->add($passager);
        }

        return $this;
    }

    public function removePassager(User $passager): static
    {
        $this->passagers->removeElement($passager);

        return $this;
    }

    public function getVehicule(): ?Vehicule
    {
        return $this->vehicule;
    }

    public function setVehicule(?Vehicule $vehicule): static
    {
        $this->vehicule = $vehicule;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getPassagersValidations(): Collection
    {
        return $this->passagersValidations;
    }

    public function addPassagerValidation(User $user): static
    {
        if (!$this->passagersValidations->contains($user)) {
            $this->passagersValidations->add($user);
        }
        return $this;
    }

    public function removePassagerValidation(User $user): static
    {
        $this->passagersValidations->removeElement($user);
        return $this;
    }
}