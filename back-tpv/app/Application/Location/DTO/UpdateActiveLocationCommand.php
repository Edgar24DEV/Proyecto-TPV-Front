<?php

namespace App\Application\Location\DTO;

class UpdateActiveLocationCommand
{
    public function __construct(
        private readonly ?int $id,         
        private readonly ?string $ubicacion,
        private readonly ?bool $activo,
        private readonly ?int $idRestaurante,
         
    ) {
    }

    // Getters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUbicacion(): ?string
    {
        return $this->ubicacion;
    }

    public function getIdRestaurante(): ?int
    {
        return $this->idRestaurante;
    }

    public function getActivo(): ?bool
    {
        return $this->activo;
    }
}
