<?php

namespace App\Application\Product\DTO;

class AddProductCommand
{
    public function __construct(
        private readonly ?string $nombre,
        private readonly ?float $precio,
        private readonly ?string $imagen,
        private readonly ?float $iva,
        private readonly ?int $idCategoria,
        private readonly ?int $idEmpresa
    ) {
    }

    // Getters

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function getIva(): ?float
    {
        return $this->iva;
    }

    public function getIdCategoria(): ?int
    {
        return $this->idCategoria;
    }

    public function getIdEmpresa(): ?int
    {
        return $this->idEmpresa;
    }
}