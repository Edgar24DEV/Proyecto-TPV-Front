<?php

namespace App\Application\Client\DTO;

class ListClientCompanyCommand
{
    public function __construct(
        private readonly ?int $idCompany
    ) {
    }

    // Getters

    public function getIdCompany(): ?int
    {
        return $this->idCompany;
    }
}
