<?php

namespace App\Entity;

use App\Repository\InformeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: InformeRepository::class)]
#[Broadcast]
class Informe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $observaciones = null;

    #[ORM\Column]
    #[Assert\GreaterThan(value: 0, message: "El dinero debe ser mayor a 0")]
    #[Assert\NotBlank(message: "Debes de ingresar una cantidad")]
    private ?int $dinero = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Debes de ingresar una imagen del grupo")]
    private ?string $imagen = null;

    #[ORM\OneToOne(cascade: ['remove'])]
    #[Assert\NotBlank(message: "Debe de existir un tour")]
    private ?Tour $Tour = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(?string $observaciones): static
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    public function getDinero(): ?int
    {
        return $this->dinero;
    }

    public function setDinero(int $dinero): static
    {
        $this->dinero = $dinero;

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(string $imagen): static
    {
        $this->imagen = $imagen;

        return $this;
    }

    public function getTour(): ?Tour
    {
        return $this->Tour;
    }

    public function setTour(?Tour $Tour): static
    {
        $this->Tour = $Tour;

        return $this;
    }
}
