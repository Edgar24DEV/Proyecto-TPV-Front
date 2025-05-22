<?php

namespace App\Infrastructure\Repositories;

use App\Application\Company\DTO\LoginCompanyCommand;
use App\Domain\Company\Entities\Company;
use App\Domain\Company\Repositories\CompanyRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Employee;
use COM;
use Hash;
use Illuminate\Support\Facades\DB;

class EloquentCompanyRepository implements CompanyRepositoryInterface
{
    public function exist(int $idCompany): bool
    {

        $results = DB::table('empresas')
            ->where('id', $idCompany)
            ->whereNull(columns: 'deleted_at')
            ->exists();

        return $results;
    }


    public function login(LoginCompanyCommand $command): Company
    {
        // Buscar el restaurante en la base de datos usando el nombre
        $result = DB::table('empresas')
            ->where('razon_social', $command->getNombre())
            ->where('contrasenya', $command->getContrasenya())
            ->whereNull('deleted_at')
            ->first(); // No es necesario el `where('contrasenya', ...)` ya que lo verificamos después


        // Si las credenciales son correctas, retornar el restaurante
        return new Company(
            id: (int) $result->id,
            nombre: (string) $result->nombre,
            direccionFiscal: (string) $result->direccion_fiscal,
            CIF: (string) $result->CIF,
            razonSocial: (string) $result->razon_social,
            telefono: (string) $result->telefono,
            correo: (string) $result->correo,
            contrasenya: (string) $result->contrasenya,
        );



    }
}