<?php

namespace App\Application\OrderLine\DTO;

class AddOrderLineCommand
{
    public function __construct(
        private readonly ?int $idOrder,
        private readonly ?int $idProduct,
        private readonly ?int $quantity,
        private readonly ?string $description,
        private readonly ?string $state
    ) {
    }

    public function getIdOrder(): ?int
    {
        return $this->idOrder;
    }

    public function getIdProduct(): ?int
    {
        return $this->idProduct;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getState(): ?string
    {
        return $this->state;
    }
}
