<?php

namespace App\Application\OrderLine\DTO;

class UpdateOrderLineCommand
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?int $quantity,
        private readonly ?string $name,
        private readonly ?float $price
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }
}
