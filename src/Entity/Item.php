<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use AssertionError;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[Broadcast]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "El título no puede estar vacío")]
    private ?string $titulo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(length: 255)]
    private ?string $foto = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[Assert\NotBlank(message: "Debe de existir una localidad")]
    private ?Localidad $Localidad = null;

    #[ORM\ManyToMany(targetEntity: Ruta::class, inversedBy: 'items')]
    private Collection $Ruta;

    public function __construct()
    {
        $this->Ruta = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setID(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(string $foto): static
    {
        $this->foto = $foto;

        return $this;
    }

    public function getLocalidad(): ?Localidad
    {
        return $this->Localidad;
    }

    public function setLocalidad(?Localidad $Localidad): static
    {
        $this->Localidad = $Localidad;

        return $this;
    }

    /**
     * @return Collection<int, Ruta>
     */
    public function getRuta(): Collection
    {
        return $this->Ruta;
    }

    public function addRutum(Ruta $rutum): static
    {
        if (!$this->Ruta->contains($rutum)) {
            $this->Ruta->add($rutum);
        }

        return $this;
    }

    public function removeRutum(Ruta $rutum): static
    {
        $this->Ruta->removeElement($rutum);

        return $this;
    }
}
