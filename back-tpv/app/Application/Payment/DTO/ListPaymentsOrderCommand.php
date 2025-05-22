<?php

namespace App\Application\Payment\DTO;

class ListPaymentsOrderCommand
{
    public function __construct(
        private readonly ?int $idPedido
    ) {
    }

    // Getters

    public function getIdPedido(): ?int
    {
        return $this->idPedido;
    }
}
