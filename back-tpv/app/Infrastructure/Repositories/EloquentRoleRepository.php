<?php

namespace App\Infrastructure\Repositories;

use App\Application\Role\DTO\AddRoleCommand;
use App\Application\Role\DTO\OneRoleCommand;
use App\Application\Role\DTO\UpdateRoleCommand;
use App\Domain\Employee\Entities\Role;
use App\Domain\Employee\Repositories\RoleRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Employee;
use Illuminate\Support\Facades\DB;

class EloquentRoleRepository implements RoleRepositoryInterface
{
    public function exist(int $idRol): bool
    {

        $results = DB::table('rols')
            ->where('id', $idRol)
            ->whereNull('deleted_at')
            ->exists();

        return $results;
    }
    public function existName(string $name, int $idEmpresa): bool
    {
        $results = DB::table('rols')
            ->where('rol', $name)
            ->where('id_empresa', $idEmpresa)
            ->whereNull('deleted_at')
            ->exists();
        return $results;
    }
    public function existwithCompany(int $idRol, int $idEmpresa): bool
    {
        $results = DB::table('rols')
            ->where('id_empresa', $idEmpresa)
            ->where('id', $idRol)
            ->whereNull('deleted_at')
            ->exists();
        return $results;
    }
    public function findByCompanyId(int $companyId): array
    {
        $results = DB::table('rols')
            ->where('id_empresa', $companyId)
            ->whereNull('deleted_at')
            ->get();

        return $results->map(function ($row) {
            return new Role(
                id: (int) $row->id,
                rol: (string) $row->rol,
                productos: (bool) $row->productos,
                categorias: (bool) $row->categorias,
                tpv: (bool) $row->tpv,
                usuarios: (bool) $row->usuarios,
                mesas: (bool) $row->mesas,
                restaurante: (bool) $row->restaurante,
                clientes: (bool) $row->clientes,
                empresa: (bool) $row->empresa,
                pago: (bool) $row->pago,
                idEmpresa: (int) $row->id_empresa,
            );
        })->toArray();
    }
    public function create(AddRoleCommand $command): Role
    {
        $id = DB::table('rols')->insertGetId([
            'rol' => $command->getRol(),
            'productos' => $command->hasProductos(),
            'categorias' => $command->hasCategorias(),
            'tpv' => $command->hasTpv(),
            'usuarios' => $command->hasUsuarios(),
            'mesas' => $command->hasMesas(),
            'restaurante' => $command->hasRestaurante(),
            'clientes' => $command->hasClientes(),
            'empresa' => $command->hasEmpresa(),
            'pago' => $command->hasPago(),
            'id_empresa' => $command->getIdEmpresa(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return new Role(
            id: $id,
            rol: $command->getRol(),
            productos: $command->hasProductos(),
            categorias: $command->hasCategorias(),
            tpv: $command->hasTpv(),
            usuarios: $command->hasUsuarios(),
            mesas: $command->hasMesas(),
            restaurante: $command->hasRestaurante(),
            clientes: $command->hasClientes(),
            empresa: $command->hasEmpresa(),
            pago: $command->hasPago(),
            idEmpresa: $command->getIdEmpresa()
        );
    }

    public function update(UpdateRoleCommand $command): Role
    {
        $id = DB::table('rols')
            ->where('id', $command->getId())
            ->update([
                'rol' => $command->getRol(),
                'productos' => $command->hasProductos(),
                'categorias' => $command->hasCategorias(),
                'tpv' => $command->hasTpv(),
                'usuarios' => $command->hasUsuarios(),
                'mesas' => $command->hasMesas(),
                'restaurante' => $command->hasRestaurante(),
                'clientes' => $command->hasClientes(),
                'empresa' => $command->hasEmpresa(),
                'pago' => $command->hasPago(),
                'id_empresa' => $command->getIdEmpresa(),
                'updated_at' => now(),
            ]);

        return new Role(
            id: $command->getId(),
            rol: $command->getRol(),
            productos: $command->hasProductos(),
            categorias: $command->hasCategorias(),
            tpv: $command->hasTpv(),
            usuarios: $command->hasUsuarios(),
            mesas: $command->hasMesas(),
            restaurante: $command->hasRestaurante(),
            clientes: $command->hasClientes(),
            empresa: $command->hasEmpresa(),
            pago: $command->hasPago(),
            idEmpresa: $command->getIdEmpresa()
        );
    }
    public function delete(int $id): bool
    {
        $delete = DB::table('rols')
            ->where('id', $id)
            ->update(['deleted_at' => now()]);

        return $delete > 0;
    }

    public function findById(OneRoleCommand $command): ?Role
    {
        $row = DB::table('rols')
            ->where('id', $command->getId())
            ->first();

        if (!$row) {
            return null;
        }

        return new Role(
            id: $row->id,
            rol: $row->rol,
            productos: (bool) $row->productos,
            categorias: (bool) $row->categorias,
            tpv: (bool) $row->tpv,
            usuarios: (bool) $row->usuarios,
            mesas: (bool) $row->mesas,
            restaurante: (bool) $row->restaurante,
            clientes: (bool) $row->clientes,
            empresa: (bool) $row->empresa,
            pago: (bool) $row->pago,
            idEmpresa: $row->id_empresa
        );
    }



}