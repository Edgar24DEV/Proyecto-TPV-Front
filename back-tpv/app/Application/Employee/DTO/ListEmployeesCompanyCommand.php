<?php

namespace App\Application\Employee\DTO;

class ListEmployeesCompanyCommand
{
    public function __construct(
        private readonly ?int $idEmpresa
    ) {
    }

    // Getters

    public function getIdEmpresa(): ?int
    {
        return $this->idEmpresa;
    }
}
