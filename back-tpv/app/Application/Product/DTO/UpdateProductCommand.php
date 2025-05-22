<?php

namespace App\Application\Product\DTO;

use PhpParser\Node\Expr\Cast\Double;

class UpdateProductCommand
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $nombre,
        private readonly ?float $precio,
        private readonly ?string $imagen,
        private readonly ?bool $activo,
        private readonly ?int $idCategoria,
        private readonly ?float $iva,
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

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function getActivo(): ?bool
    {
        return $this->activo;
    }

    public function getCategoria(): ?int
    {
        return $this->idCategoria;
    }

    public function getIva(): ?float
    {
        return $this->iva;
    }

}
