<?php

namespace App\Application\Product\DTO;

class ListProductsCompanyCommand
{
    public function __construct(
        private readonly ?int $idEmpresa
    ) {
    }

    // Getters

    public function getIdEmpresa(): ?int
    {
        return $this->idEmpresa;
    }
}
