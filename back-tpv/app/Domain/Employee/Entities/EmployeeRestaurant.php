<?php

namespace App\Domain\Employee\Entities;

class EmployeeRestaurant
{
    private int $id;
    private int $idEmpleado;
    private int $idRestaurante;
    private string $createdAt;
    private string $updatedAt;

    public function __construct(
        ?int $id,
        int $idEmpleado,
        int $idRestaurante
    ) {
        $this->id = $id;
        $this->idEmpleado = $idEmpleado;
        $this->idRestaurante = $idRestaurante;
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getIdEmpleado(): int
    {
        return $this->idEmpleado;
    }

    public function getIdRestaurante(): int
    {
        return $this->idRestaurante;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    // Setters
    public function setIdEmpleado(int $idEmpleado): void
    {
        $this->idEmpleado = $idEmpleado;
    }

    public function setIdRestaurante(int $idRestaurante): void
    {
        $this->idRestaurante = $idRestaurante;
    }

    // Puedes añadir métodos de negocio si es necesario
}
