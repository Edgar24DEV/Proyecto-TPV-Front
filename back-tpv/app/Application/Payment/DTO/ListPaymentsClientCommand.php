<?php

namespace App\Application\Payment\DTO;

class ListPaymentsClientCommand
{
    public function __construct(
        private readonly ?int $idCliente
    ) {
    }

    // Getters

    public function getIdPedido(): ?int
    {
        return $this->idCliente;
    }
}
