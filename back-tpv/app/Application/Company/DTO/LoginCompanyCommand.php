<?php

namespace App\Application\Company\DTO;

class LoginCompanyCommand
{
    public function __construct(
        private readonly ?string $nombre,
        private readonly ?string $contrasenya
    ) {
    }

    // Getters

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function getContrasenya(): ?string
    {
        return $this->contrasenya;
    }
}
