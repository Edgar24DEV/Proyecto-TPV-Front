<?php

namespace App\Application\Role\DTO;

class UpdateRoleCommand
{
    public function __construct(
        private readonly ?int $id,
        private readonly string $rol,
        private readonly bool $productos,
        private readonly bool $categorias,
        private readonly bool $tpv,
        private readonly bool $usuarios,
        private readonly bool $mesas,
        private readonly bool $restaurante,
        private readonly bool $clientes,
        private readonly bool $empresa,
        private readonly bool $pago,
        private readonly ?int $idEmpresa,
    ) {
    }
    //Getter
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
