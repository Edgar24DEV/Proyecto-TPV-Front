<?php

namespace App\Application\Role\DTO;

class ListRolesCompanyCommand
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
