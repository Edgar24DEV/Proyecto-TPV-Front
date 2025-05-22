<?php

namespace App\Domain\Product\Entities;

use App\Domain\Company\Entities\Company;
use App\Domain\Product\Entities\Category;
use App\Domain\Restaurant\Entities\Restaurant;
use App\Domain\Order\Entities\OrderLine;
use PhpParser\Node\Expr\Cast\Double;

class Product
{


    public function __construct(
        public ?int $id,
        public ?string $nombre,
        public ?float $precio,
        public ?string $imagen,
        public ?bool $activo,
        public ?float $iva,
        public ?int $idCategoria,
        public ?int $idEmpresa
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->imagen = $imagen;
        $this->activo = $activo;
        $this->iva = $iva;
        $this->idCategoria = $idCategoria;
        $this->idEmpresa = $idEmpresa;
    }

    public function getPrecio(): ?float
    {
        return $this->precio;
    }
    public function getNombre(): ?string
    {
        return $this->nombre;
    }
    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function getActivo(): ?bool
    {
        return $this->activo;
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
