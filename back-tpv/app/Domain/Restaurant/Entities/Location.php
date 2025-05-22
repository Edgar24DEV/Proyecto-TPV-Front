<?php

namespace App\Domain\Restaurant\Entities;

use App\Domain\Restaurant\Entities\Restaurant;
use App\Domain\Table\Entities\Table;
use App\Domain\Shared\ValueObjects\activoStatus;

class Location
{

    public function __construct(
        public ?int $id,
        public ?string $ubicacion,
        public ?bool $activoStatus,
        public ?int $idRestaurante,
        public ?array $tables = []
    ) {
        $this->id = $id;
        $this->ubicacion = $ubicacion;
        $this->activoStatus = $activoStatus;
        $this->idRestaurante = $idRestaurante;
        $this->tables = $tables;
    }

    // Comportamiento de negocio
    public function activate(): void
    {
        $this->activoStatus = true;
    }

    public function deactivate(): void
    {
        $this->activoStatus = false;
    }
    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUbicacion(): ?string
    {
        return $this->ubicacion;
    }

    public function getActivoStatus(): ?bool
    {
        return $this->activoStatus;
    }

    public function getIdRestaurante(): ?int
    {
        return $this->idRestaurante;
    }

}