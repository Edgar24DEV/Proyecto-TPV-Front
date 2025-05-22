<?php

namespace App\Domain\Order\Entities;

class Order
{

    // Constructor para inicializar la entidad
    public function __construct(
        public ?int $id,
        public ?string $estado,
        public ?\DateTimeInterface $fechaInicio,
        public ?\DateTimeInterface $fechaFin,
        public ?int $comensales,
        public ?int $idMesa,

    ) {
        $this->id = $id;
        $this->estado = $estado;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->comensales = $comensales;
        $this->idMesa = $idMesa;

    }
    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function getFechaInicio(): ?\DateTimeInterface
    {
        return $this->fechaInicio;
    }

    public function getFechaFin(): ?\DateTimeInterface
    {
        return $this->fechaFin;
    }

    public function getComensales(): ?int
    {
        return $this->comensales;
    }

    public function getIdMesa(): ?int
    {
        return $this->idMesa;
    }

}
