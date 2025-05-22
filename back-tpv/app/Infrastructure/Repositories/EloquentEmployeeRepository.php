<?php

namespace App\Infrastructure\Repositories;

use App\Application\Employee\DTO\AddEmployeeCommand;
use App\Application\Employee\DTO\AddEmployeeRestaurantCommand;
use App\Application\Employee\DTO\DeleteEmployeeRestaurantCommand;
use App\Application\Employee\DTO\LoginEmployeeCommand;
use App\Application\Employee\DTO\UpdateEmployeeCommand;
use App\Application\Employee\DTO\UpdateEmployeeRoleCommand;
use App\Domain\Employee\Repositories\EmployeeRepositoryInterface;
use App\Domain\Employee\Entities\Employee;
use App\Domain\Restaurant\Entities\Restaurant;
use Illuminate\Support\Facades\DB;
use Termwind\ValueObjects\Node;

class EloquentEmployeeRepository implements EmployeeRepositoryInterface
{
    public function exist(int $idEmpleado): bool
    {
        $results = DB::table('empleados')
            ->where('id', $idEmpleado)
            ->whereNull('deleted_at')
            ->exists();

        return $results;
    }
    public function find(int $restaurant_id): array
    {
        /*
        return Employee::whereHas('restaurantes', function ($query) use ($restaurant_id) {
            $query->where('id_restaurante', $restaurant_id);
        })->get()->toArray();
        */

        $results = DB::table('empleados')
            ->join('empleado_restaurante', 'empleados.id', '=', 'empleado_restaurante.id_empleado')
            ->where('empleado_restaurante.id_restaurante', $restaurant_id)
            ->whereNull(columns: 'empleados.deleted_at')
            ->select('empleados.*')
            ->get();

        return $results->map(function ($row) {
            return new Employee(
                id: $row->id,
                nombre: $row->nombre,
                pin: $row->pin,
                idEmpresa: $row->id_empresa,
                idRol: $row->id_rol,
            );
        })->toArray();
    }

    public function create(AddEmployeeCommand $command): Employee
    {

        $id = DB::table('empleados')->insertGetId([
            'nombre' => $command->getNombre(),
            'pin' => $command->getPin(),
            'id_empresa' => $command->getIdEmpresa(),
            'id_rol' => $command->getIdRol(),
            'created_at' => Now(),
            'updated_at' => Now(),
        ]);

        $empleadoRestauranteId = DB::table('empleado_restaurante')->insertGetId([
            'id_empleado' => $id,
            'id_restaurante' => $command->getIdRestaurante(),
        ]);


        // Retorna una nueva instancia con el ID generado
        return new Employee(
            id: $id,
            nombre: $command->getNombre(),
            pin: $command->getPin(),
            idEmpresa: $command->getIdEmpresa(),
            idRol: $command->getIdRol(),
        );

    }
    public function update(UpdateEmployeeCommand $employee): Employee
    {
        $employeeId = $employee->getId();
        $result = DB::table('empleados')
            ->where('id', $employeeId)
            ->whereNull('deleted_at')
            ->first();
        $id = DB::table('empleados')->where('id', $employeeId)
            ->update([
                'nombre' => $employee->getNombre(),
                'pin' => $employee->getPin() ?? $result->pin,
                'id_empresa' => $employee->getIdEmpresa(),
                'id_rol' => $employee->getIdRol(),
                'updated_at' => Now(),
            ]);
        // Retorna una nueva instancia con el ID generado
        return new Employee(
            id: $id,
            nombre: $employee->getNombre(),
            pin: $employee->getPin(),
            idEmpresa: $employee->getIdEmpresa(),
            idRol: $employee->getIdRol()
        );
    }
    public function delete(int $id): bool
    {
        $idDelete = DB::table('empleados')
            ->where('id', $id)
            ->delete();
        return $idDelete > 0;
    }

    public function login(LoginEmployeeCommand $employee): Employee
    {
        $result = DB::table('empleados')
            ->where('id', $employee->getId())
            ->where('pin', $employee->getPin())
            ->whereNull('deleted_at')
            ->first();

        return new Employee(
            id: (int) $result->id,
            nombre: (string) $result->nombre,
            pin: (string) $result->pin,
            idRol: (int) $result->id_rol,
            idEmpresa: (int) $result->id_empresa
        );
    }

    public function findById(int $idEmpleado): Employee
    {
        $result = DB::table('empleados')
            ->where('id', $idEmpleado)
            ->whereNull('deleted_at')
            ->first();

        return new Employee(
            id: (int) $result->id,
            nombre: (string) $result->nombre,
            pin: (string) $result->pin,
            idRol: (int) $result->id_rol,
            idEmpresa: (int) $result->id_empresa
        );
    }
    public function updateRole(UpdateEmployeeRoleCommand $employee): Employee
    {
        $employeeId = $employee->getId();
        $id = DB::table('empleados')->where('id', $employeeId)
            ->update([

                'id_rol' => $employee->getIdRol(),
                'updated_at' => Now(),
            ]);
        $updateEmployee = DB::table('empleados')->where('id', $employeeId)->first();
        // Retorna una nueva instancia con el ID generado
        return new Employee(
            id: $updateEmployee->id,
            nombre: $updateEmployee->nombre,
            pin: $updateEmployee->pin,
            idEmpresa: $updateEmployee->id_empresa,
            idRol: $updateEmployee->id_rol
        );
    }

    public function softDelete(int $id): bool
    {
        return DB::table('empleados')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->update([
                'deleted_at' => now(),
                'updated_at' => now()
            ]) > 0;
    }

    public function findByCompany(int $idEmpresa): array
    {
        $results = DB::table('empleados')
            ->where('empleados.id_empresa', $idEmpresa)
            ->whereNull(columns: 'empleados.deleted_at')
            ->select('empleados.*')
            ->get();

        return $results->map(function ($row) {
            return new Employee(
                id: $row->id,
                nombre: $row->nombre,
                pin: $row->pin,
                idEmpresa: $row->id_empresa,
                idRol: $row->id_rol,
            );
        })->toArray();
    }

    public function findEmployeeRestaurantsByCompany(int $idEmpresa, int $idEmployee): array
    {
        $results = DB::table('empleados')
            ->join('empleado_restaurante', 'empleados.id', '=', 'empleado_restaurante.id_empleado')
            ->join('restaurantes', 'restaurantes.id', '=', 'empleado_restaurante.id_restaurante')
            ->where('empleados.id_empresa', $idEmpresa)
            ->where('empleados.id', $idEmployee)
            ->whereNull(columns: 'empleados.deleted_at')
            ->select('restaurantes.*')
            ->get();

        return $results->map(function ($row) {
            return new Restaurant(
                id: (int) $row->id,
                nombre: (string) $row->nombre,
                direccion: (string) $row->direccion,
                telefono: (string) $row->telefono,
                contrasenya: (string) $row->contrasenya,
                direccionFiscal: (string) $row->direccion_fiscal,
                cif: (string) $row->CIF,
                razonSocial: (string) $row->razon_social,
                idEmpresa: (int) $row->id_empresa,
            );
        })->toArray();
    }
    public function deleteEmployeeRestaurant(DeleteEmployeeRestaurantCommand $command): bool
    {
        return DB::table('empleado_restaurante')
            ->where('id_empleado', $command->getIdEmpleado())
            ->where('id_restaurante', $command->getIdRestaurante())
            ->delete() > 0;
    }
    public function addEmployeeRestaurant(AddEmployeeRestaurantCommand $command): bool
    {
        return DB::table('empleado_restaurante')->insertGetId([
            'id_empleado' => $command->getIdEmpleado(),
            'id_restaurante' => $command->getIdRestaurante(),
            'created_at' => now(),
            'updated_at' => now(),
        ]) > 0;
    }


}
