<?php

namespace App\Application\Employee\DTO;

class UpdateEmployeeCommand
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $nombre,
        private readonly ?string $pin,
        private readonly ?int $idEmpresa,
        private readonly ?int $idRol
    ) {
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function getPin(): ?string
    {
        return $this->pin;
    }

    public function getIdEmpresa(): ?int
    {
        return $this->idEmpresa;
    }

    public function getIdRol(): ?int
    {
        return $this->idRol;
    }
}
