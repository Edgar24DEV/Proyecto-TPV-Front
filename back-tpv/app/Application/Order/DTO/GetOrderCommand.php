<?php

namespace App\Application\Order\DTO;

class GetOrderCommand
{
    public function __construct(
        private readonly ?int $idMesa,
    ) {
    }

    // Getters
    public function getIdMesa(): ?int
    {
        return $this->idMesa;
    }

}
