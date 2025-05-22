<?php

namespace App\Application\Employee\DTO;

class FindByIdEmployeeCommand
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
