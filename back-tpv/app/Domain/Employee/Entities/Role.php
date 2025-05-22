<?php

namespace App\Domain\Employee\Entities;

class Role
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $rol,
        public readonly bool $productos,
        public readonly bool $categorias,
        public readonly bool $tpv,
        public readonly bool $usuarios,
        public readonly bool $mesas,
        public readonly bool $restaurante,
        public readonly bool $clientes,
        public readonly bool $empresa,
        public readonly bool $pago,
        public readonly ?int $idEmpresa = null,
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRol(): string
    {
        return $this->rol;
    }

    public function hasProductos(): bool
    {
        return $this->productos;
    }

    public function hasCategorias(): bool
    {
        return $this->categorias;
    }

    public function hasTpv(): bool
    {
        return $this->tpv;
    }

    public function hasUsuarios(): bool
    {
        return $this->usuarios;
    }

    public function hasMesas(): bool
    {
        return $this->mesas;
    }

    public function hasRestaurante(): bool
    {
        return $this->restaurante;
    }

    public function hasClientes(): bool
    {
        return $this->clientes;
    }

    public function hasEmpresa(): bool
    {
        return $this->empresa;
    }

    public function hasPago(): bool
    {
        return $this->pago;
    }

    public function getIdEmpresa(): ?int
    {
        return $this->idEmpresa;
    }
}
