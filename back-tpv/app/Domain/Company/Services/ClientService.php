<?php

namespace App\Domain\Company\Services;

use App\Domain\Company\Entities\Client;
use Illuminate\Http\Request;

class ClientService
{

    public function showClientInfo($clients)
    {

        return collect($clients)->map(function ($row) {
            return new Client(
                id: $row->id,
                razon_social: $row->razon_social,
                cif: $row->cif,
                direccion_fiscal: $row->direccion_fiscal,
                correo: $row->correo,
                id_empresa: $row->id_empresa
            );
        });
    }

    public function showClientSimpleInfo(Client $row): Client
    {
        return new Client(
            id: $row->id,
            razon_social: $row->razon_social,
            cif: $row->cif,
            direccion_fiscal: $row->direccion_fiscal,
            correo: $row->correo,
            id_empresa: $row->id_empresa,
        );
    }
    
}
