<?php

namespace App\Application\Restaurant\DTO;

class UpdateRestaurantCommand
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $nombre,
        private readonly ?string $direccion,
        private readonly ?string $telefono,
        private readonly ?string $contrasenya = null,
        private readonly ?string $direccionFiscal,
        private readonly ?string $cif,
        private readonly ?string $razonSocial,
        private readonly ?int $idEmpresa
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

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function getContrasenya(): ?string
    {
        return $this->contrasenya;
    }

    public function getDireccionFiscal(): ?string
    {
        return $this->direccionFiscal;
    }

    public function getCif(): ?string
    {
        return $this->cif;
    }

    public function getRazonSocial(): ?string
    {
        return $this->razonSocial;
    }

    public function getIdEmpresa(): ?int
    {
        return $this->idEmpresa;
    }
}
