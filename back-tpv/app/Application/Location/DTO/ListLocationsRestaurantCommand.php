<?php

namespace App\Application\Location\DTO;

class ListLocationsRestaurantCommand
{
    public function __construct(
        private readonly ?int $idRestaurante
    ) {
    }

    // Getters

    public function getIdRestaurant(): ?int
    {
        return $this->idRestaurante;
    }
}
