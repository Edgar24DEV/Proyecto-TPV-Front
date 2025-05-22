<?php

namespace App\Application\Order\DTO;

class AddOrderCommand
{
    public function __construct(
        private readonly ?int $comensales,
        private readonly ?int $idMesa,
    ) {
    }

    // Getters
    public function getIdMesa(): ?int
    {
        return $this->idMesa;
    }

    public function getComensales(): ?int
    {
        return $this->comensales;
    }

}
