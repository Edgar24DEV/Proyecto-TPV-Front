<?php

namespace App\Application\Employee\DTO;

class DeleteEmployeeRestaurantCommand
{
    public function __construct(
        private readonly ?int $idEmpleado,
        private readonly ?int $idRestaurante,
    ) {
    }

    // Getters

    public function getIdEmpleado(): ?int
    {
        return $this->idEmpleado;
    }
    public function getIdRestaurante(): ?int
    {
        return $this->idRestaurante;
    }
}
