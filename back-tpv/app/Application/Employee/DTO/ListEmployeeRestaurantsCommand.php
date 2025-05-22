<?php

namespace App\Application\Employee\DTO;

class ListEmployeeRestaurantsCommand
{
    public function __construct(
        private readonly ?int $idEmpresa,
        private readonly ?int $idEmpleado
    ) {
    }

    // Getters

    public function getIdEmpresa(): ?int
    {
        return $this->idEmpresa;
    }
    public function getIdEmpleado(): ?int
    {
        return $this->idEmpleado;
    }
}
