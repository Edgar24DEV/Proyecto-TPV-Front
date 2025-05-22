<?php
namespace App\Domain\Employee\Services;
use App\Domain\Employee\Entities\Employee;
use Illuminate\Http\Request;
class EmployeeService
{

    public function showEmployeeInfo($employees)
    {

        return collect($employees)->map(function ($row) {
            return new Employee(
                id: $row->id,
                nombre: $row->nombre,
                idRol: $row->idRol,
                idEmpresa: $row->idEmpresa,
            );
        });
    }
    public function showEmployeeInfoSimple($employees)
    {
        return new Employee(
            id: $employees->id,
            nombre: $employees->nombre,
            idRol: $employees->idRol,
            idEmpresa: $employees->idEmpresa,
        );
    }


}