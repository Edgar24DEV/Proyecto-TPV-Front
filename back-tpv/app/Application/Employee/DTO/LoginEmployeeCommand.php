<?php

namespace App\Application\Employee\DTO;

class LoginEmployeeCommand
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?int $pin
    ) {
    }

    // Getters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPin(): ?int
    {
        return $this->pin;
    }
}
