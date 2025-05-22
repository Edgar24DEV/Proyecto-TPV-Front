<?php

namespace App\Domain\Employee\Entities;

use App\Infrastructure\Persistence\Eloquent\Company;
use App\Infrastructure\Persistence\Eloquent\Restaurant;
use App\Infrastructure\Persistence\Eloquent\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee
{

    //use HasFactory;
    /*
    protected $table = "empleados";
    protected $fillable = ['nombre', 'pin', 'id_empresa', 'id_rol'];

    public function empresa()
    {
        return $this->belongsTo(Company::class, 'id_empresa');
    }

    public function rol()
    {
        return $this->belongsTo(Role::class, 'id_rol');
    }

    public function restaurantes()
    {
        return $this->belongsToMany(Restaurant::class, 'empleado_restaurante', 'id_empleado', 'id_restaurante');
    }
        */


    public function __construct(
        public readonly ?int $id,
        public readonly string $nombre,
        public readonly int $idRol,
        public readonly ?int $pin = null,
        public readonly ?int $idEmpresa = null,
    ) {
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getPin(): int
    {
        return $this->pin;
    }

    public function getIdRol(): int
    {
        return $this->idRol;
    }

    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getId(
    ): int {
        return $this->id;
    }
    public function getIdEmpresa(): int
    {
        return $this->idEmpresa;
    }







}
