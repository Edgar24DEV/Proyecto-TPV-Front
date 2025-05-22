<?php

namespace App\Application\Client\DTO;

class FindClientCifCommand
{
    public function __construct(
        private readonly string $cif,
    ) {}

    public function getCif(): string
    {
        return $this->cif;
    }
}
