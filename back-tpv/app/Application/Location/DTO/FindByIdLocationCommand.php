<?php

namespace App\Application\Location\DTO;

class FindByIdLocationCommand
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
