<?php

namespace App\Entity;

use App\Repository\VehiculeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
class Vehicule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $marque = null;

    #[ORM\Column(length: 255)]
    private ?string $modele = null;

    #[ORM\Column(length: 255)]
    private ?string $couleur = null;

    #[ORM\Column(length: 255)]
    private ?string $energie = null;

    #[ORM\Column(length: 255)]
    private ?string $plaqueImmatriculation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datePremiereImmatriculation = null;

    /**
     * @var Collection<int, Covoiturage>
     */
    #[ORM\OneToMany(targetEntity: Covoiturage::class, mappedBy: 'vehicule')]
    private Collection $covoiturages;

    public function __construct()
    {
        $this->covoiturages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): static
    {
        $this->modele = $modele;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): static
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getEnergie(): ?string
    {
        return $this->energie;
    }

    public function setEnergie(string $energie): static
    {
        $this->energie = $energie;

        return $this;
    }

    public function getPlaqueImmatriculation(): ?string
    {
        return $this->plaqueImmatriculation;
    }

    public function setPlaqueImmatriculation(string $plaqueImmatriculation): static
    {
        $this->plaqueImmatriculation = $plaqueImmatriculation;

        return $this;
    }

    public function getDatePremiereImmatriculation(): ?\DateTimeInterface
    {
        return $this->datePremiereImmatriculation;
    }

    public function setDatePremiereImmatriculation(\DateTimeInterface $datePremiereImmatriculation): static
    {
        $this->datePremiereImmatriculation = $datePremiereImmatriculation;

        return $this;
    }

    /**
     * @return Collection<int, Covoiturage>
     */
    public function getCovoiturages(): Collection
    {
        return $this->covoiturages;
    }

    public function addCovoiturage(Covoiturage $covoiturage): static
    {
        if (!$this->covoiturages->contains($covoiturage)) {
            $this->covoiturages->add($covoiturage);
            $covoiturage->setVehicule($this);
        }

        return $this;
    }

    public function removeCovoiturage(Covoiturage $covoiturage): static
    {
        if ($this->covoiturages->removeElement($covoiturage)) {
            // set the owning side to null (unless already changed)
            if ($covoiturage->getVehicule() === $this) {
                $covoiturage->setVehicule(null);
            }
        }

        return $this;
    }
}
