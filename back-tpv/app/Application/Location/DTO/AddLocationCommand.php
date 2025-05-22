<?php

namespace App\Application\Location\DTO;

class AddLocationCommand
{
    public function __construct(
        private readonly ?string $ubicacion,
        private readonly ?int $idRestaurante
    ) {
    }

    // Getters

    public function getUbicacion(): ?string
    {
        return $this->ubicacion;
    }

    public function getIdRestaurante(): ?int
    {
        return $this->idRestaurante;
    }
}
