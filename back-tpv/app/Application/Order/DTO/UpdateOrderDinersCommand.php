<?php

namespace App\Application\Order\DTO;

class UpdateOrderDinersCommand
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?int $comensales,
    ) {
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComensales(): ?string
    {
        return $this->comensales;
    }

}
