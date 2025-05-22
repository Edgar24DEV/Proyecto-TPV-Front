<?php

namespace App\Application\Order\DTO;

class GetCompanyOrdersCommand
{
    public function __construct(
        private readonly ?int $idEmpresa,
    ) {
    }

    // Getters
    public function getIdEmpresa(): ?int
    {
        return $this->idEmpresa;
    }

}
