<?php

namespace App\Application\Product\DTO;

class UpdateDeactivateProductCommand
{
    public function __construct(
        private readonly ?bool $activo,
        private readonly ?int $idProducto,
    ) {
    }

    // Getters

    public function getActivo(): ?bool
    {
        return $this->activo;
    }

    public function getIdProducto(): ?int
    {
        return $this->idProducto;
    }

}