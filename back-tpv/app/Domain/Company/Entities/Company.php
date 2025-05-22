<?php

namespace App\Domain\Company\Entities;

use App\Http\Controllers\CategoriaController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domain\Employee\Entities\Employee;
use App\Domain\Restaurant\Entities\Restaurant;
use App\Domain\Product\Entities\Product;
use App\Domain\Product\Entities\Category;

class Company
{

    public function __construct(
        public readonly ?int $id,
        public readonly ?string $nombre,
        public readonly ?string $direccionFiscal,
        public readonly ?string $CIF,
        public readonly ?string $razonSocial,
        public readonly ?string $telefono,
        public readonly ?string $correo,
        public readonly ?string $contrasenya
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

    public function getDireccionFiscal(): string
    {
        return $this->direccionFiscal;
    }

    public function getCIF(): string
    {
        return $this->CIF;
    }

    public function getRazonSocial(): string
    {
        return $this->razonSocial;
    }

    public function getTelefono(): string
    {
        return $this->telefono;
    }

    public function getCorreo(): string
    {
        return $this->correo;
    }

    public function getContrasenya(): string
    {
        return $this->contrasenya;
    }
}

