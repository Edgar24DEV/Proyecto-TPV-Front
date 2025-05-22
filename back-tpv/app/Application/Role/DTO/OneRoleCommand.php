<?php

namespace App\Application\Role\DTO;

class OneRoleCommand
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
