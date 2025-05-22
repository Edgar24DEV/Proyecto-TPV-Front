<?php

namespace App\Application\Product\DTO;

class UpdateProductRestaurantCommand
{
    public function __construct(
        private readonly ?bool $activo,
        private readonly ?int $idProducto,
        private readonly ?int $idRestaurante,
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

    public function getIdRestaurante(): ?int
    {
        return $this->idRestaurante;
    }
}