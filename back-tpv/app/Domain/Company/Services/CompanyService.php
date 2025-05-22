<?php
namespace App\Domain\Company\Services;

use App\Domain\Company\Entities\Company;
use Illuminate\Http\Request;
class CompanyService
{

    public function showCompanyInfo($company)
    {

        return collect($company)->map(function ($row) {
            return new Company(
                id: $row->id,
                nombre: $row->nombre,
                direccionFiscal: $row->direccionFiscal,
                CIF: $row->CIF,
                razonSocial: $row->razonSocial,
                telefono: $row->telefono,
                correo: $row->correo,
                contrasenya: null,
            );
        });
    }
    public function showCompanyInfoSimple($companies)
    {
        return new Company(
            id: $companies->id,
            nombre: $companies->nombre,
            direccionFiscal: $companies->direccionFiscal,
            CIF: $companies->CIF,
            razonSocial: $companies->razonSocial,
            telefono: $companies->telefono,
            correo: $companies->correo,
            contrasenya: null,
        );
    }
}