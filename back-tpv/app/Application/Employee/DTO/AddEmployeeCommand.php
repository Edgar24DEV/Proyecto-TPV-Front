<?php

namespace App\Application\Employee\DTO;

class AddEmployeeCommand
{
    public function __construct(
        private readonly ?string $nombre,
        private readonly ?string $pin,
        private readonly ?int $idEmpresa,
        private readonly ?int $idRol,
        private readonly ?int $idRestaurante
    ) {
    }

    // Getters

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

    public function getIdRestaurante(): ?int
    {
        return $this->idRestaurante;
    }
}
