<?php

namespace App\Application\Payment\DTO;

class ListAllPaymentsCommand
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
