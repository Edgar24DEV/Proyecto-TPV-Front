<?php

namespace App\Application\Restaurant\DTO;

class ListRestaurantsCompanyCommand
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
