<?php

namespace App\Entity;

use App\Repository\ReparationCarosserieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReparationCarosserieRepository::class)
 */
class ReparationCarosserie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=ReparationECar::class, mappedBy="reparation")
     */
    private $reparationECars;

    /**
     * @ORM\OneToMany(targetEntity=InfosCarosserie::class, mappedBy="reparation")
     */
    private $infosCarosseries;

    public function __construct()
    {
        $this->reparationECars = new ArrayCollection();
        $this->infosCarosseries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

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
            $reparationECar->setReparation($this);
        }

        return $this;
    }

    public function removeReparationECar(ReparationECar $reparationECar): self
    {
        if ($this->reparationECars->removeElement($reparationECar)) {
            // set the owning side to null (unless already changed)
            if ($reparationECar->getReparation() === $this) {
                $reparationECar->setReparation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, InfosCarosserie>
     */
    public function getInfosCarosseries(): Collection
    {
        return $this->infosCarosseries;
    }

    public function addInfosCarosseries(InfosCarosserie $infosCarosseries): self
    {
        if (!$this->infosCarosseries->contains($infosCarosseries)) {
            $this->infosCarosseries[] = $infosCarosseries;
            $infosCarosseries->setReparation($this);
        }

        return $this;
    }

    public function removeInfosCarosseries(InfosCarosserie $infosCarosseries): self
    {
        if ($this->infosCarosseries->removeElement($infosCarosseries)) {
            // set the owning side to null (unless already changed)
            if ($infosCarosseries->getReparation() === $this) {
                $infosCarosseries->setReparation(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->libelle;
    }
}
