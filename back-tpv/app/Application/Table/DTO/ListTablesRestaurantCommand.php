<?php

namespace App\Application\Table\DTO;

class ListTablesRestaurantCommand
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
