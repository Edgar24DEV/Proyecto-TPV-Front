<?php

namespace App\Application\Product\DTO;

class GetProductRestaurantCommand
{
    public function __construct(
        private readonly ?int $idProducto,
        private readonly ?int $idRestaurante,
    ) {
    }

    // Getters


    public function getIdProducto(): ?int
    {
        return $this->idProducto;
    }

    public function getIdRestaurante(): ?int
    {
        return $this->idRestaurante;
    }

}