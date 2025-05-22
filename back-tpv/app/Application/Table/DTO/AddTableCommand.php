<?php

namespace App\Application\Table\DTO;

class AddTableCommand
{
    public function __construct(
        private readonly ?string $mesa,
        private readonly ?int $idUbicacion,
        private readonly ?int $posX,
        private readonly ?int $posY,
    ) {
    }

    // Getters

    public function getMesa(): ?string
    {
        return $this->mesa;
    }

    public function getIdUbicacion(): ?int
    {
        return $this->idUbicacion;
    }

    public function getPosX(): ?int
    {
        return $this->posX;
    }

    public function getPosY(): ?int
    {
        return $this->posY;
    }
}
