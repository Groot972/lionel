<?php

namespace App\Entity;

use App\Repository\ReparationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReparationRepository::class)
 */
class Reparation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $libelle;


    /**
     * @ORM\OneToMany(targetEntity=ReparationEtat::class, mappedBy="reparation")
     */
    private $reparationEtats;

    /**
     * @ORM\OneToMany(targetEntity=Infos::class, mappedBy="reparation")
     */
    private $infos;


    public function __construct()
    {

        $this->reparationEtats = new ArrayCollection();
        $this->infos = new ArrayCollection();

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
            $reparationEtat->setReparation($this);
        }

        return $this;
    }

    public function removeReparationEtat(ReparationEtat $reparationEtat): self
    {
        if ($this->reparationEtats->removeElement($reparationEtat)) {
            // set the owning side to null (unless already changed)
            if ($reparationEtat->getReparation() === $this) {
                $reparationEtat->setReparation(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->libelle;
    }

    /**
     * @return Collection<int, Infos>
     */
    public function getInfos(): Collection
    {
        return $this->infos;
    }

    public function addInfo(Infos $info): self
    {
        if (!$this->infos->contains($info)) {
            $this->infos[] = $info;
            $info->setReparation($this);
        }

        return $this;
    }

    public function removeInfo(Infos $info): self
    {
        if ($this->infos->removeElement($info)) {
            // set the owning side to null (unless already changed)
            if ($info->getReparation() === $this) {
                $info->setReparation(null);
            }
        }

        return $this;
    }

}
