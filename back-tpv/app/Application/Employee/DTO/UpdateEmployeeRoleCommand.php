<?php

namespace App\Application\Employee\DTO;

class UpdateEmployeeRoleCommand
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?int $idRol,
    ) {
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdRol(): ?int
    {
        return $this->idRol;
    }

}
