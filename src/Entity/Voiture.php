<?php

namespace App\Entity;

use App\Repository\VoitureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VoitureRepository::class)
 */
class Voiture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="voitures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity=ReparationEtat::class, mappedBy="voiture", cascade={"remove"} )
     */
    private $reparationEtats;

    /**
     * @ORM\OneToOne(targetEntity=Images::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $marque;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $modele;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=ReparationECar::class, mappedBy="voiture")
     */
    private $reparationECars;

    public function __construct()
    {
        $this->reparationEtats = new ArrayCollection();
        $this->reparationECars = new ArrayCollection();
    }




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?client
    {
        return $this->client;
    }

    public function setClient(?client $client): self
    {
        $this->client = $client;

        return $this;
    }



    /**
     * @return Collection<int, ReparationEtat>
     */
    public function getReparationEtats(): Collection
    {
        return $this->reparationEtats;
    }

    public function addReparationEtat(ReparationEtat $reparationEtat): self
    {
        if (!$this->reparationEtats->contains($reparationEtat)) {
            $this->reparationEtats[] = $reparationEtat;
            $reparationEtat->setVoiture($this);
        }

        return $this;
    }

    public function removeReparationEtat(ReparationEtat $reparationEtat): self
    {
        if ($this->reparationEtats->removeElement($reparationEtat)) {
            // set the owning side to null (unless already changed)
            if ($reparationEtat->getVoiture() === $this) {
                $reparationEtat->setVoiture(null);
            }
        }

        return $this;
    }

    public function getImage(): ?Images
    {
        return $this->image;
    }

    public function setImage(Images $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, ReparationECar>
     */
    public function getReparationECars(): Collection
    {
        return $this->reparationECars;
    }

    public function addReparationECar(ReparationECar $reparationECar): self
    {
        if (!$this->reparationECars->contains($reparationECar)) {
            $this->reparationECars[] = $reparationECar;
            $reparationECar->setVoiture($this);
        }

        return $this;
    }

    public function removeReparationECar(ReparationECar $reparationECar): self
    {
        if ($this->reparationECars->removeElement($reparationECar)) {
            // set the owning side to null (unless already changed)
            if ($reparationECar->getVoiture() === $this) {
                $reparationECar->setVoiture(null);
            }
        }

        return $this;
    }


}
