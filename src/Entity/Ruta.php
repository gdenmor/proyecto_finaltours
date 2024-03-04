<?php

namespace App\Entity;

use App\Repository\RutaRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RutaRepository::class)]
#[Broadcast]
class Ruta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "El nombre de la ruta no puede estar vacía")]
    private ?string $titulo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $foto = null;

    #[ORM\OneToMany(mappedBy: 'Ruta', targetEntity: Tour::class, orphanRemoval: true)]
    private Collection $tours;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\Expression("this.getFechaInicio() >= new DateTime()",message: "La fecha debe ser mayor o igual a la fecha actual.")]
    private ?DateTime $fecha_inicio = null;
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\Expression("this.getFechaInicio() <= this.getFechaFin()",message: "La fecha de fin debe ser mayor a la fecha de inicio.")]
    private ?DateTime $fecha_fin = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Debe de existir un punto de encuentro")]
    private ?string $Punto_Encuentro;

    #[ORM\Column]
    #[Assert\LessThanOrEqual(value: 20, message: "El aforo tiene un máximo de 20 personas")]
    private ?int $Aforo;

    #[ORM\Column(nullable: true)]
    private ?array $Programacion = null;

    #[ORM\ManyToMany(targetEntity: Item::class, mappedBy: 'Ruta')]
    private Collection $items;

    public function __construct()
    {
        $this->tours = new ArrayCollection();
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Tour>
     */
    public function getTours(): Collection
    {
        return $this->tours;
    }

    public function addTour(Tour $tour): static
    {
        if (!$this->tours->contains($tour)) {
            $this->tours->add($tour);
            $tour->setRuta($this);
        }

        return $this;
    }

    public function removeTour(Tour $tour): static
    {
        if ($this->tours->removeElement($tour)) {
            // set the owning side to null (unless already changed)
            if ($tour->getRuta() === $this) {
                $tour->setRuta(null);
            }
        }

        return $this;
    }
    public function getFechaInicio(): ?DateTime
    {
        return $this->fecha_inicio;
    }

    public function setFechaInicio(DateTime $fecha_inicio): static
    {
        $this->fecha_inicio = $fecha_inicio;

        return $this;
    }

    public function getFechaFin(): ?DateTime
    {
        return $this->fecha_fin;
    }

    public function setFechaFin(DateTime $fecha_fin): static
    {
        $this->fecha_fin = $fecha_fin;

        return $this;
    }

    public function getPuntoEncuentro(): ?string
    {
        return $this->Punto_Encuentro;
    }

    public function setPuntoEncuentro(string $Punto_Encuentro): static
    {
        $this->Punto_Encuentro = $Punto_Encuentro;

        return $this;
    }

    public function getAforo(): ?int
    {
        return $this->Aforo;
    }

    public function setAforo(int $Aforo): static
    {
        $this->Aforo = $Aforo;

        return $this;
    }

    public function getProgramacion(): ?array
    {
        return $this->Programacion;
    }

    public function setProgramacion(?array $Programacion): static
    {
        $this->Programacion = $Programacion;

        return $this;
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
            $item->addRutum($this);
        }

        return $this;
    }

    public function removeItem(Item $item): static
    {
        if ($this->items->removeElement($item)) {
            $item->removeRutum($this);
        }

        return $this;
    }

    public function __toString(){
        return $this->titulo;
    }
}
