<?php

namespace App\Entity;

use App\Repository\ProvinciaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProvinciaRepository::class)]
#[Broadcast]
class Provincia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "El nombre de la provincia no puede estar vacía")]
    private ?string $nombre = null;

    #[ORM\OneToMany(mappedBy: 'Provincia', targetEntity: Localidad::class)]
    private Collection $localidads;

    public function __construct()
    {
        $this->localidads = new ArrayCollection();
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

    /**
     * @return Collection<int, Localidad>
     */
    public function getLocalidads(): Collection
    {
        return $this->localidads;
    }

    public function addLocalidad(Localidad $localidad): static
    {
        if (!$this->localidads->contains($localidad)) {
            $this->localidads->add($localidad);
            $localidad->setProvincia($this);
        }

        return $this;
    }

    public function removeLocalidad(Localidad $localidad): static
    {
        if ($this->localidads->removeElement($localidad)) {
            // set the owning side to null (unless already changed)
            if ($localidad->getProvincia() === $this) {
                $localidad->setProvincia(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->nombre;
    }
}
