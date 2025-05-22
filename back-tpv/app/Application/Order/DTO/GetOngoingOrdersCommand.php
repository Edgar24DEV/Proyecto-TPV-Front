<?php

namespace App\Application\Order\DTO;

class GetOngoingOrdersCommand
{
    public function __construct(
        private readonly ?int $idRestaurante,
    ) {
    }

    // Getters
    public function getIdRestaurante(): ?int
    {
        return $this->idRestaurante;
    }

}
