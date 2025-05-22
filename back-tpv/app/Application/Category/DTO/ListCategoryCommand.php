<?php

namespace App\Application\Category\DTO;

class ListCategoryCommand
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
