<?php

namespace App\Domain\Employee\Services;

use App\Domain\Employee\Entities\Role;
use Illuminate\Support\Collection;

class RoleService
{
    public function showRoleInfo($roles): Collection
    {
        return collect($roles)->map(function ($row) {
            return new Role(
                id: $row->id,
                rol: $row->rol,
                productos: $row->productos,
                categorias: $row->categorias,
                tpv: $row->tpv,
                usuarios: $row->usuarios,
                mesas: $row->mesas,
                restaurante: $row->restaurante,
                clientes: $row->clientes,
                empresa: $row->empresa,
                pago: $row->pago,
                idEmpresa: $row->idEmpresa
            );
        });
    }

    public function showRoleInfoSimple($role): Role
    {
        return new Role(
            id: $role->id,
            rol: $role->rol,
            productos: $role->productos,
            categorias: $role->categorias,
            tpv: $role->tpv,
            usuarios: $role->usuarios,
            mesas: $role->mesas,
            restaurante: $role->restaurante,
            clientes: $role->clientes,
            empresa: $role->empresa,
            pago: $role->pago,
            idEmpresa: $role->idEmpresa
        );
    }
}
