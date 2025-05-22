<?php

namespace App\Infrastructure\Repositories;

use App\Application\Client\DTO\AddClientCompanyCommand;
use App\Application\Client\DTO\UpdateClientCompanyCommand;
use App\Domain\Company\Entities\Client;
use App\Domain\Company\Repositories\ClientRepositoryInterface;
use Illuminate\Support\Facades\DB;

class EloquentClientRepository implements ClientRepositoryInterface
{
    public function exist(int $idcliente): bool
    {
        $results = DB::table('clientes')
            ->where('id', $idcliente)
            ->whereNull(columns: 'deleted_at')
            ->exists();

        return $results;
    }

    public function findByCompany(int $idCompany): array
    {
        $results = DB::table('clientes')
            ->where('id_empresa', $idCompany)
            ->select('clientes.*')
            ->whereNull('clientes.deleted_at')

            ->get();

        return $results->map(function ($row) {
            return new Client(
                id: $row->id,
                razon_social: $row->razon_social,
                cif: $row->cif,
                direccion_fiscal: $row->direccion_fiscal,
                correo: $row->correo,
                id_empresa: $row->id_empresa
            );
        })->toArray();
    }



    public function create(AddClientCompanyCommand $command): Client
    {
        $id = DB::table('clientes')->insertGetId([
            'razon_social'     => $command->getRazonSocial(),
            'cif'              => $command->getCif(),
            'direccion_fiscal' => $command->getDireccionFiscal(),
            'correo'          => $command->getCorreo(),
            'id_empresa'       => $command->getIdEmpresa(),
            'created_at'       => now(),
            'updated_at'      => now(),
        ]);

        // Retrieve the newly created client to ensure all data is correct
        $clientData = DB::table('clientes')->where('id', $id)->first();

        return new Client(
            id: $clientData->id,
            razon_social: $clientData->razon_social,
            direccion_fiscal: $clientData->direccion_fiscal,
            cif: $clientData->cif,
            correo: $clientData->correo,
            id_empresa: $clientData->id_empresa
        );
    }
    public function update(UpdateClientCompanyCommand $command): ?Client
    {
        $updateData = array_filter([
            'razon_social' => $command->getRazonSocial(),
            'cif' => $command->getCif(),
            'direccion_fiscal' => $command->getDireccionFiscal(),
            'correo' => $command->getCorreo(),
            'id_empresa' => $command->getIdEmpresa(),
            'updated_at' => now(),
        ], fn($value) => !is_null($value));

        if (empty($updateData)) {
            return $this->find($command->getId());
        }

        $isUpdate = DB::table('clientes')
            ->where('id', $command->getId())
            ->update($updateData) > 0;

        if ($isUpdate) {
            return $this->find($command->getId());
        }

        return null;
    }

    public function find(int $id): ?Client
    {
        $clientData = DB::table('clientes')
        ->where('id', $id)
        ->whereNull('deleted_at')
        ->first(); 

        return $clientData ? new Client(
            id: $clientData->id,
            razon_social: $clientData->razon_social,
            cif: $clientData->cif,
            direccion_fiscal: $clientData->direccion_fiscal,
            correo: $clientData->correo,
            id_empresa: $clientData->id_empresa
        ) : null;
    }


    public function findByCif(string $cif): Client
    {
        $query = DB::table('clientes')->where(
            'cif',
            $cif
        )->whereNull('deleted_at');



        $clientData = $query->first();

        return  new Client(
            id: $clientData->id,
            razon_social: $clientData->razon_social,
            cif: $clientData->cif,
            direccion_fiscal: $clientData->direccion_fiscal,
            correo: $clientData->correo,
            id_empresa: $clientData->id_empresa
        );
    }

    public function softDelete(int $id): bool
    {
        return DB::table('clientes')
            ->where('id', $id)
            ->whereNull('deleted_at') 
            ->update([
                'deleted_at' => now(),
                'updated_at' => now() 
            ]) > 0;
    }
}
