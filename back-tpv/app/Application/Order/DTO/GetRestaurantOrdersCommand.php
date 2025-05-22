<?php

namespace App\Application\Order\DTO;

class GetRestaurantOrdersCommand
{
    public function __construct(
        private readonly ?int $idRestaurante,
    ) {
    }

    // Getters
    public function getIdRestaurante(): ?int
    {
        return $this->idRestaurante;
    }

}
