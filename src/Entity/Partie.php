<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PartieRepository")
 */
class Partie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json_array")
     */
    private $partie_terrain_1;

    /**
     * @ORM\Column(type="json_array")
     */
    private $partie_terrain_2;

    /**
     * @ORM\Column(type="integer")
     */
    private $partie_tour;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $partie_status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $partie_nom;

    /**
     * @ORM\Column(type="json_array")
     */
    private $partie_des;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPartieTerrain1()
    {
        return $this->partie_terrain_1;
    }

    public function setPartieTerrain1($partie_terrain_1): self
    {
        $this->partie_terrain_1 = $partie_terrain_1;

        return $this;
    }

    public function getPartieTerrain2()
    {
        return $this->partie_terrain_2;
    }

    public function setPartieTerrain2($partie_terrain_2): self
    {
        $this->partie_terrain_2 = $partie_terrain_2;

        return $this;
    }

    public function getPartieTour(): ?int
    {
        return $this->partie_tour;
    }

    public function setPartieTour(int $partie_tour): self
    {
        $this->partie_tour = $partie_tour;

        return $this;
    }

    public function getPartieStatus(): ?int
    {
        return $this->partie_status;
    }

    public function setPartieStatus(?int $partie_status): self
    {
        $this->partie_status = $partie_status;

        return $this;
    }

    public function getPartieNom(): ?string
    {
        return $this->partie_nom;
    }

    public function setPartieNom(?string $partie_nom): self
    {
        $this->partie_nom = $partie_nom;

        return $this;
    }

    public function getPartieDes()
    {
        return $this->partie_des;
    }

    public function setPartieDes($partie_des): self
    {
        $this->partie_des = $partie_des;

        return $this;
    }
}
