<?php

namespace App\Application\Client\DTO;

class FindByIdClientCommand
{
    public function __construct(
        private readonly ?int $id
    ) {
    }

    // Getters

    public function getId(): ?int
    {
        return $this->id;
    }
}
