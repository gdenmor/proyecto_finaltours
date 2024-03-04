<?php

namespace App\Entity;

use App\Repository\TourRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TourRepository::class)]
#[Broadcast]
class Tour
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tours')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: "Debe de existir una ruta para este tour")]
    private ?Ruta $Ruta;

    #[ORM\OneToMany(mappedBy: 'tour', targetEntity: Reserva::class, orphanRemoval: true)]
    private Collection $Reserva;

    #[ORM\ManyToOne(inversedBy: 'tours')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: "Debe de existir un guia para este tour")]
    private ?User $Guia=null;

    #[ORM\OneToMany(mappedBy: 'Tour', targetEntity: Valoracion::class)]
    private Collection $valoracions;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "Debe de existir una fecha para este tour")]
    private ?\DateTimeInterface $Fecha = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Assert\NotBlank(message: "Debe de existir una hora para este tour")]
    private ?\DateTimeInterface $Hora = null;

    #[ORM\OneToOne(mappedBy: 'Tour', targetEntity: Informe::class, orphanRemoval: true, cascade: ["remove"])]
    private ?Informe $informe = null;

    #[ORM\Column(nullable: true)]
    private ?int $num_personas_asisten = null;

    public function getInforme(): ?Informe
    {
        return $this->informe;
    }

    public function setInforme(?Informe $informe): static
    {
        $this->informe = $informe;

        // Seteamos el tour en el informe para establecer la relación inversa
        if ($informe !== null) {
            $informe->setTour($this);
        }

        return $this;
    }

    
    public function __construct()
    {
        $this->Reserva = new ArrayCollection();
        $this->valoracions = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): ?int
    {
        return $this->id=$id;
    }

    public function getRuta(): ?Ruta
    {
        return $this->Ruta;
    }

    public function setRuta(?Ruta $Ruta): static
    {
        $this->Ruta = $Ruta;

        return $this;
    }

    /**
     * @return Collection<int, Reserva>
     */
    public function getReserva(): Collection
    {
        return $this->Reserva;
    }

    public function addReserva(Reserva $reserva): static
    {
        if (!$this->Reserva->contains($reserva)) {
            $this->Reserva->add($reserva);
            $reserva->setTour($this);
        }

        return $this;
    }

    public function removeReserva(Reserva $reserva): static
    {
        if ($this->Reserva->removeElement($reserva)) {
            // set the owning side to null (unless already changed)
            if ($reserva->getTour() === $this) {
                $reserva->setTour(null);
            }
        }

        return $this;
    }

    public function getGuia(): ?User
    {
        return $this->Guia;
    }

    public function setGuia(?User $Guia): static
    {
        $this->Guia = $Guia;

        return $this;
    }

    /**
     * @return Collection<int, Valoracion>
     */
    public function getValoracions(): Collection
    {
        return $this->valoracions;
    }

    public function addValoracion(Valoracion $valoracion): static
    {
        if (!$this->valoracions->contains($valoracion)) {
            $this->valoracions->add($valoracion);
            $valoracion->setTour($this);
        }

        return $this;
    }

    public function removeValoracion(Valoracion $valoracion): static
    {
        if ($this->valoracions->removeElement($valoracion)) {
            // set the owning side to null (unless already changed)
            if ($valoracion->getTour() === $this) {
                $valoracion->setTour(null);
            }
        }

        return $this;
    }
    public function getFecha(): ?\DateTimeInterface
    {
        return $this->Fecha;
    }

    public function setFecha(\DateTimeInterface $Fecha): static
    {
        $this->Fecha = $Fecha;

        return $this;
    }

    public function getHora(): ?\DateTimeInterface
    {
        return $this->Hora;
    }

    public function setHora(\DateTimeInterface $Hora): static
    {
        $this->Hora = $Hora;

        return $this;
    }

    public function __toString(){
        return $this->getRuta()->getTitulo()." ". $this->id;
    }

    public function getNumPersonasAsisten(): ?int
    {
        return $this->num_personas_asisten;
    }

    public function setNumPersonasAsisten(?int $num_personas_asisten): static
    {
        $this->num_personas_asisten = $num_personas_asisten;

        return $this;
    }
}
