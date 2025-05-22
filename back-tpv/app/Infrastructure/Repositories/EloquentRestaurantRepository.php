<?php

namespace App\Infrastructure\Repositories;

use App\Application\Restaurant\DTO\AddRestaurantCommand;
use App\Application\Restaurant\DTO\AddRestaurantProductCommand;
use App\Application\Restaurant\DTO\ListRestaurantCompanyCommand;
use App\Application\Restaurant\DTO\LoginRestaurantCommand;
use App\Application\Restaurant\DTO\UpdateRestaurantCommand;
use App\Domain\Employee\Repositories\RestaurantRepositoryInterface;
use App\Domain\Restaurant\Entities\Restaurant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EloquentRestaurantRepository implements RestaurantRepositoryInterface
{
    public function exist(int $idRestaurant): bool
    {

        $results = DB::table('restaurantes')
            ->where('id', $idRestaurant)
            ->whereNull('deleted_at')
            ->exists();

        return $results;
    }
    public function existwithCompany(int $idRestaurant, int $idComany): bool
    {

        $results = DB::table('restaurantes')
            ->where('id', $idRestaurant)
            ->where('id_empresa', $idComany)
            ->whereNull('deleted_at')
            ->exists();

        return $results;
    }
    public function existName(string $name): bool
    {
        $results = DB::table('restaurantes')
            ->where('nombre', $name)
            ->whereNull('deleted_at')
            ->exists();
        return $results;
    }

    public function login(LoginRestaurantCommand $command): Restaurant
    {
        // Buscar el restaurante en la base de datos usando el nombre
        $result = DB::table('restaurantes')
            ->where('nombre', $command->getNombre())
            ->whereNull('deleted_at')
            ->first(); // No es necesario el `where('contrasenya', ...)` ya que lo verificamos después

        // Verificar si el restaurante existe y la contraseña es correcta
        if ($result && Hash::check($command->getContrasenya(), $result->contrasenya)) {
            // Si las credenciales son correctas, retornar el restaurante
            return new Restaurant(
                id: (int) $result->id,
                nombre: (string) $result->nombre,
                direccion: (string) $result->direccion,
                telefono: (string) $result->telefono,
                contrasenya: (string) $result->contrasenya, // Nota: la contraseña nunca se debe mostrar, solo se usa para la comparación
                direccionFiscal: (string) $result->direccion_fiscal,
                cif: (string) $result->CIF,
                razonSocial: (string) $result->razon_social,
                idEmpresa: (int) $result->id_empresa,
            );
        }

        // Si las credenciales no son correctas
        throw new \Exception('Credenciales incorrectas');
    }


    public function saveRestaurantProduct(AddRestaurantProductCommand $command): AddRestaurantProductCommand
    {
        $id = DB::table('restaurante_producto')->insertGetId([
            'activo' => $command->getActivo() ?? true,
            'id_producto' => $command->getIdProducto(),
            'id_restaurante' => $command->getIdRestaurante(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $result = DB::table('restaurante_producto')
            ->where('id', '=', $id)
            ->first();

        return new AddRestaurantProductCommand(
            $result->activo,
            $result->id_producto,
            $result->id_restaurante,
        );

    }


    public function create(AddRestaurantCommand $command): Restaurant
    {
        // Insertar el restaurante, cifrando la contraseña
        $id = DB::table('restaurantes')->insertGetId([
            'nombre' => $command->getNombre(),
            'direccion' => $command->getDireccion(),
            'telefono' => $command->getTelefono(),
            'contrasenya' => Hash::make($command->getContrasenya()), // Cifra la contraseña
            'direccion_fiscal' => $command->getDireccionFiscal(),
            'cif' => $command->getCif(),
            'razon_social' => $command->getRazonSocial(),
            'id_empresa' => $command->getIdEmpresa(),
            'created_at' => Now(),
            'updated_at' => Now(),
        ]);

        // Retorna una nueva instancia de Restaurant con los datos
        return new Restaurant(
            id: $id,
            nombre: $command->getNombre(),
            direccion: $command->getDireccion(),
            telefono: $command->getTelefono(),
            contrasenya: $command->getContrasenya(),
            direccionFiscal: $command->getDireccionFiscal(),
            cif: $command->getCif(),
            razonSocial: $command->getRazonSocial(),
            idEmpresa: $command->getIdEmpresa(),
        );
    }
    public function update(UpdateRestaurantCommand $command): Restaurant
    {
        $id = DB::table('restaurantes')
            ->where('id', $command->getId())
            ->update([
                'nombre' => $command->getNombre(),
                'direccion' => $command->getDireccion(),
                'telefono' => $command->getTelefono(),
                //'contrasenya' => Hash::make($command->getContrasenya()),
                'direccion_fiscal' => $command->getDireccionFiscal(),
                'cif' => $command->getCif(),
                'razon_social' => $command->getRazonSocial(),
                'id_empresa' => $command->getIdEmpresa(),
                'updated_at' => Now(), // Actualizar la fecha de modificación
            ]);

        // Retorna una nueva instancia de Restaurant con los datos
        return new Restaurant(
            id: $id,
            nombre: $command->getNombre(),
            direccion: $command->getDireccion(),
            telefono: $command->getTelefono(),
            contrasenya: $command->getContrasenya(),
            direccionFiscal: $command->getDireccionFiscal(),
            cif: $command->getCif(),
            razonSocial: $command->getRazonSocial(),
            idEmpresa: $command->getIdEmpresa(),
        );
    }

    public function findByCompanyId(int $companyId): array
    {
        $results = DB::table('restaurantes')
            ->where('id_empresa', $companyId)
            ->whereNull('deleted_at')
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

    public function softDelete(int $id): bool
    {
        return DB::table('restaurantes')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->update([
                'deleted_at' => now(),
                'updated_at' => now()
            ]) > 0;
    }
    public function find(int $id): Restaurant
{
    $row = DB::table('restaurantes')
        ->where('id', $id)
        ->whereNull('deleted_at')
        ->first();

    if (!$row) {
        throw new \Exception("Restaurante no encontrado.");
    }

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
}

    public function findById(int $id): Restaurant
    {
        $results = DB::table('restaurantes')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->first();


        return new Restaurant(
            id: (int) $results->id,
            nombre: (string) $results->nombre,
            direccion: (string) $results->direccion,
            telefono: (string) $results->telefono,
            contrasenya: (string) $results->contrasenya,
            direccionFiscal: (string) $results->direccion_fiscal,
            cif: (string) $results->CIF,
            razonSocial: (string) $results->razon_social,
            idEmpresa: (int) $results->id_empresa,
        );
    }





}
