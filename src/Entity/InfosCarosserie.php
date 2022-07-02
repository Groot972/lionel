<?php

namespace App\Entity;

use App\Repository\InfosCarosserieRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InfosCarosserieRepository::class)
 */
class InfosCarosserie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $infos;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $marque_voiture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $modele_voiture;

    /**
     * @ORM\ManyToOne(targetEntity=ReparationCarosserie::class, inversedBy="infosCarosseries")
     */
    private $reparation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInfos(): ?string
    {
        return $this->infos;
    }

    public function setInfos(string $infos): self
    {
        $this->infos = $infos;

        return $this;
    }

    public function getMarqueVoiture(): ?string
    {
        return $this->marque_voiture;
    }

    public function setMarqueVoiture(string $marque_voiture): self
    {
        $this->marque_voiture = $marque_voiture;

        return $this;
    }

    public function getModeleVoiture(): ?string
    {
        return $this->modele_voiture;
    }

    public function setModeleVoiture(string $modele_voiture): self
    {
        $this->modele_voiture = $modele_voiture;

        return $this;
    }

    public function getReparation(): ?ReparationCarosserie
    {
        return $this->reparation;
    }

    public function setReparation(?ReparationCarosserie $reparation): self
    {
        $this->reparation = $reparation;

        return $this;
    }
}
