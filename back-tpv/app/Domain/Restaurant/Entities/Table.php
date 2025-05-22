<?php

namespace App\Domain\Restaurant\Entities;

use App\Domain\Order\Entities\Order;
use App\Domain\Location\Entities\Location;

class Table
{
    public int $id;
    public string $mesa;
    public bool $activo;
    public int $idUbicacion;
    public ?int $posX;
    public ?int $posY;

    public function __construct(
        int $id,
        string $mesa,
        bool $activo,
        int $idUbicacion,
        ?int $posX = null,
        ?int $posY = null
    ) {
        $this->id = $id;
        $this->mesa = $mesa;
        $this->activo = $activo;
        $this->idUbicacion = $idUbicacion;
        $this->posX = $posX;
        $this->posY = $posY;
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getMesa(): string
    {
        return $this->mesa;
    }

    public function isActivo(): bool
    {
        return $this->activo;
    }

    public function getIdUbicacion(): int
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

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setMesa(string $mesa): void
    {
        $this->mesa = $mesa;
    }

    public function setActivo(bool $activo): void
    {
        $this->activo = $activo;
    }

    public function setIdUbicacion(int $idUbicacion): void
    {
        $this->idUbicacion = $idUbicacion;
    }

    public function setPosX(?int $posX): void
    {
        $this->posX = $posX;
    }

    public function setPosY(?int $posY): void
    {
        $this->posY = $posY;
    }
}
