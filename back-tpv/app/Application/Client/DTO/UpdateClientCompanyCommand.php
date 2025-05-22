<?php

namespace App\Application\Client\DTO;

class UpdateClientCompanyCommand
{
    public function __construct(
        private readonly int $id,
        private readonly ?string $razonSocial,
        private readonly ?string $cif,
        private readonly ?string $direccionFiscal,
        private readonly ?string $correo,
        private readonly ?int $idEmpresa,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getRazonSocial(): ?string
    {
        return $this->razonSocial;
    }

    public function getCif(): ?string
    {
        return $this->cif;
    }

    public function getDireccionFiscal(): ?string
    {
        return $this->direccionFiscal;
    }

    public function getCorreo(): ?string
    {
        return $this->correo;
    }

    public function getIdEmpresa(): ?int
    {
        return $this->idEmpresa;
    }
}