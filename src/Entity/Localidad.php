<?php

namespace App\Entity;

use App\Repository\LocalidadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LocalidadRepository::class)]
#[Broadcast]
class Localidad
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "El nombre de la localidad no puede estar vacío")]
    private ?string $nombre = null;

    #[ORM\ManyToOne(inversedBy: 'localidads')]
    #[Assert\NotBlank(message: "El nombre de la provincia no puede estar vacío")]
    private ?Provincia $Provincia = null;

    #[ORM\OneToMany(mappedBy: 'Localidad', targetEntity: Ruta::class)]
    private Collection $rutas;

    #[ORM\OneToMany(mappedBy: 'Localidad', targetEntity: Item::class)]
    private Collection $items;

    public function __construct()
    {
        $this->rutas = new ArrayCollection();
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getProvincia(): ?Provincia
    {
        return $this->Provincia;
    }

    public function setProvincia(?Provincia $Provincia): static
    {
        $this->Provincia = $Provincia;

        return $this;
    }

    /**
     * @return Collection<int, Ruta>
     */
    public function getRutas(): Collection
    {
        return $this->rutas;
    }

    public function addRuta(Ruta $ruta): static
    {
        if (!$this->rutas->contains($ruta)) {
            $this->rutas->add($ruta);

            // Asociar la localidad a cada item en la ruta
            foreach ($ruta->getItems() as $item) {
                if ($item->getLocalidad() !== $this) {
                    $item->setLocalidad($this);
                }
            }
        }

        return $this;
    }

    public function removeRuta(Ruta $ruta): static
    {
        if ($this->rutas->removeElement($ruta)) {
            // Desasociar la localidad de cada item en la ruta
            foreach ($ruta->getItems() as $item) {
                if ($item->getLocalidad() === $this) {
                    $item->setLocalidad(null);
                }
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->nombre;
    }

    /**
     * @return Collection<int, Item>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setLocalidad($this);
        }

        return $this;
    }

    public function removeItem(Item $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getLocalidad() === $this) {
                $item->setLocalidad(null);
            }
        }

        return $this;
    }
}
