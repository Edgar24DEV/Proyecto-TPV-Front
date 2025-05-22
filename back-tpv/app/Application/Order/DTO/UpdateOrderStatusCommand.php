<?php

namespace App\Application\Order\DTO;

class UpdateOrderStatusCommand
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $estado,
    ) {
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

}
