<?php

namespace App\Application\Restaurant\DTO;

class DeleteRestaurantCommand
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
