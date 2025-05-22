<?php

namespace App\Application\OrderLine\DTO;

class ListOrderLineTableCommand
{
    public function __construct(
        private readonly ?int $idOrderLine
    ) {}

    public function getIdOrderLine(): ?int
    {
        return $this->idOrderLine;
    }
}
