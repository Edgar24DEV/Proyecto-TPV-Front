<?php

namespace App\Application\Category\DTO;

class UpdateCategoryCommand
{
    public function __construct(
        private readonly ?int $id,         
        private readonly ?string $categoria,
        private readonly ?bool $activo,
        private readonly ?int $idEmpresa,
         
    ) {
    }

    // Getters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoria(): ?string
    {
        return $this->categoria;
    }

    public function getIdEmpresa(): ?int
    {
        return $this->idEmpresa;
    }

    public function getActivo(): ?bool
    {
        return $this->activo;
    }
}
