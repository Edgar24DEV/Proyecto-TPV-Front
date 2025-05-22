<?php

namespace App\Domain\Restaurant\Entities;

use App\Domain\Company\Entities\Company;
use App\Domain\Product\Entities\Product;
use App\Domain\Employee\Entities\Employee;
use App\Domain\Restaurant\Entities\Location;

class Restaurant
{


    private array $productos = [];
    private array $ubicaciones = [];
    private array $empleados = [];

    public function __construct(
        public readonly ?int $id,
        public readonly ?string $nombre,
        public readonly ?string $direccion,
        public readonly ?string $telefono,
        public readonly ?string $contrasenya,
        public readonly ?string $direccionFiscal,
        public readonly ?string $cif,
        public readonly ?string $razonSocial,
        public readonly ?int $idEmpresa = null,
    ) {

    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getDireccion(): string
    {
        return $this->direccion;
    }

    public function getTelefono(): string
    {
        return $this->telefono;
    }

    public function getContrasenya(): string
    {
        return $this->contrasenya;
    }

    public function getDireccionFiscal(): string
    {
        return $this->direccionFiscal;
    }

    public function getCif(): string
    {
        return $this->cif;
    }

    public function getRazonSocial(): string
    {
        return $this->razonSocial;
    }

    public function getIdEmpresa(): int
    {
        return $this->idEmpresa;
    }

    public function getProductos(): array
    {
        return $this->productos;
    }

    public function getUbicaciones(): array
    {
        return $this->ubicaciones;
    }

    public function getEmpleados(): array
    {
        return $this->empleados;
    }

    public function addProducto(Product $producto): void
    {
        $this->productos[] = $producto;
    }

    public function addUbicacion(Location $ubicacion): void
    {
        $this->ubicaciones[] = $ubicacion;
    }

    public function addEmpleado(Employee $empleado): void
    {
        $this->empleados[] = $empleado;
    }
}
