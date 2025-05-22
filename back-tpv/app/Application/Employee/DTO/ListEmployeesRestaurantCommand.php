<?php

namespace App\Application\Employee\DTO;

class ListEmployeesRestaurantCommand
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
