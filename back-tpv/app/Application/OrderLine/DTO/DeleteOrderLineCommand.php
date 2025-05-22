<?php

namespace App\Application\OrderLine\DTO;

class DeleteOrderLineCommand
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
