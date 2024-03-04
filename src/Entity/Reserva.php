<?php

namespace App\Entity;

use App\Repository\ReservaRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservaRepository::class)]
#[Broadcast]
class Reserva
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha_reserva = null;

    #[ORM\Column(length: 255)]
    #[Assert\Choice(choices: ["ACEPTADO", "CANCELADO"], message: "Debe de tener un estado aceptado o cancelado")]
    private ?string $estado = null;

    #[ORM\ManyToOne(inversedBy: 'reservas')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: "Debe de existir un usuario que realiza la reserva")]
    private ?User $Usuario = null;

    #[ORM\ManyToOne(inversedBy: 'Reserva')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: "Debe de existir un tour del cual se realiza la reserva")]
    private ?Tour $tour = null;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(value: 1, message: "Debe de ser mayor o igual a 1 persona")]
    private ?int $num_personas = null;

    #[ORM\OneToOne(mappedBy: 'Reserva', targetEntity: Valoracion::class, cascade: ['persist', 'remove'])]
    private ?Valoracion $valoracion = null;

    public function getValoracion(): ?Valoracion
    {
        return $this->valoracion;
    }

    public function setValoracion(?Valoracion $valoracion): self
    {
        $this->valoracion = $valoracion;
        
        if ($valoracion !== null && $valoracion->getReserva() !== $this) {
            $valoracion->setReserva($this);
        }

        return $this;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getfecha_reserva(): ?\DateTimeInterface
    {
        return $this->fecha_reserva;
    }

    public function setFechaReserva(\DateTimeInterface $fecha_reserva): static
    {
        $this->fecha_reserva = $fecha_reserva;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): static
    {
        $this->estado = $estado;

        return $this;
    }

    public function getUsuario(): ?User
    {
        return $this->Usuario;
    }

    public function setUsuario(?User $Usuario): static
    {
        $this->Usuario = $Usuario;

        return $this;
    }

    public function getTour(): ?Tour
    {
        return $this->tour;
    }

    public function setTour(?Tour $tour): static
    {
        $this->tour = $tour;

        return $this;
    }

    public function getNumPersonas(): ?int
    {
        return $this->num_personas;
    }

    public function setNumPersonas(int $num_personas): static
    {
        $this->num_personas = $num_personas;

        return $this;
    }

    public function __toString(){
        return "Reserva: ".$this->fecha_reserva->format("d/m/y")." ".$this->getUsuario()->getUsername();
    }
}
