<?php

namespace App\Application\Category\DTO;

class AddCategoryCommand
{
    public function __construct(
        private readonly ?string $categoria,
        private readonly ?int $idEmpresa
    ) {
    }

    // Getters

    public function getCategoria(): ?string
    {
        return $this->categoria;
    }

    public function getIdEmpresa(): ?int
    {
        return $this->idEmpresa;
    }
}
