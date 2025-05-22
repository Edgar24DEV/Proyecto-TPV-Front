<?php

namespace App\Domain\Product\Entities;

use App\Domain\Company\Entities\Company;
use App\Domain\Product\Entities\Product;

class Category
{


    // Constructor para inicializar la entidad
    public function __construct(
        public ?int $id,
        public ?string $categoria,
        public ?bool $activo,
        public ?int $idEmpresa
    ) {
        $this->id = $id;
        $this->categoria = $categoria;
        $this->activo = $activo;
        $this->idEmpresa = $idEmpresa;
    }

    // Getter y setter para las propiedades
    public function getId()
    {
        return $this->id;
    }

    public function getCategoria()
    {
        return $this->categoria;
    }

    public function isActivo()
    {
        return $this->activo;
    }

    public function getEmpresaId()
    {
        return $this->idEmpresa;
    }
}

