<?php

namespace App\Application\Table\DTO;

class UpdateTableCommand
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $mesa,
        private readonly ?bool $activo,
        private readonly ?int $idUbicacion,
        private readonly ?int $posX,
        private readonly ?int $posY,
    ) {
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMesa(): ?string
    {
        return $this->mesa;
    }

    public function getActivo(): ?bool
    {
        return $this->activo;
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
