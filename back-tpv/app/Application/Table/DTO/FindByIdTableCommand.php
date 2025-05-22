<?php

namespace App\Application\Table\DTO;

class FindByIdTableCommand
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
