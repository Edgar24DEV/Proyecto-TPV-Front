<?php

namespace App\Infrastructure\Repositories;

use App\Application\Location\DTO\AddLocationCommand;
use App\Application\Location\DTO\UpdateActiveLocationCommand;
use App\Application\Location\DTO\UpdateLocationCommand;
use App\Application\Table\DTO\AddTableCommand;
use App\Application\Table\DTO\LoginTableCommand;
use App\Application\Table\DTO\UpdateTableCommand;
use App\Domain\Restaurant\Entities\Location;
use App\Domain\Restaurant\Repositories\LocationRepositoryInterface;
use App\Domain\Restaurant\Entities\Table;
use Illuminate\Support\Facades\DB;
use Termwind\ValueObjects\Node;

class EloquentLocationRepository implements LocationRepositoryInterface
{
    public function exist(int $idEmpleado): bool
    {
        $results = DB::table('ubicaciones')
            ->where('id', $idEmpleado)
            ->whereNull('deleted_at')
            ->exists();
        return $results;
    }
    public function find(int $restaurant_id): array
    {
        /*
        return Table::whereHas('restaurantes', function ($query) use ($restaurant_id) {
            $query->where('id_restaurante', $restaurant_id);
        })->get()->toArray();
        */

        $results = DB::table('ubicaciones')
            ->where('ubicaciones.id_restaurante', $restaurant_id)
            ->whereNull('deleted_at')
            ->select('ubicaciones.*')
            ->get();

        return $results->map(function ($row) {
            return new Location(
                id: $row->id,
                ubicacion: $row->ubicacion,
                activoStatus: $row->activo,
                idRestaurante: $row->id_restaurante,
            );
        })->toArray();
    }
    public function existLocationRestaurant(string $ubicacion, int $idRestaurante): bool
    {
        $results = DB::table('ubicaciones')
            ->where('ubicaciones.ubicacion', $ubicacion)
            ->where('ubicaciones.id_restaurante', $idRestaurante)
            ->whereNull('deleted_at')
            ->exists();
        return $results;
    }
    public function existLocationRestaurantByID(int $idUbicacion, int $idRestaurante): bool
    {
        $results = DB::table('ubicaciones')
            ->where('ubicaciones.id', $idUbicacion)
            ->where('ubicaciones.id_restaurante', $idRestaurante)
            ->whereNull('deleted_at')
            ->exists();
        return $results;
    }

    public function create(AddLocationCommand $command): Location
    {
        $id = DB::table('ubicaciones')->insertGetId([
            'ubicacion' => $command->getUbicacion(),
            'activo' => true,
            'id_restaurante' => $command->getIdRestaurante(),
            'created_at' => Now(),
            'updated_at' => Now(),
        ]);

        return new Location(
            id: $id,
            ubicacion: $command->getUbicacion(),
            activoStatus: true,
            idRestaurante: $command->getIdRestaurante(),
        );
    }
    public function update(UpdateLocationCommand $location): Location
    {
        $results = DB::table('ubicaciones')
            ->where('ubicaciones.id', $location->getId())
            ->select('ubicaciones.*')
            ->first();

        $id = DB::table('ubicaciones')
            ->where('id', $location->getId())
            ->update([
                'ubicacion' => $location->getUbicacion(),
                'activo' => $location->getActivo(),
                'updated_at' => Now(),
            ]);
        if ($results->activo != $location->getActivo()) {
            DB::table('mesas')
                ->where('id_ubicacion', $location->getId())
                ->update([
                    'activo' => $location->getActivo(),
                    'updated_at' => now(),
                ]);
        }

        // Retorna una nueva instancia con el ID generado
        return new Location(
            id: $id,
            ubicacion: $location->getUbicacion(),
            activoStatus: $location->getActivo(),
            idRestaurante: $location->getIdRestaurante(),
        );
    }
    public function updateActive(UpdateActiveLocationCommand $command): Location
    {
        // Actualiza el estado de la ubicación
        DB::table('ubicaciones')
            ->where('id', $command->getId())
            ->update([
                'activo' => $command->getActivo(),
                'updated_at' => now(),
            ]);

        // Actualiza el estado de todas las mesas relacionadas
        DB::table('mesas')
            ->where('id_ubicacion', $command->getId())
            ->update([
                'activo' => $command->getActivo(),
                'updated_at' => now(),
            ]);

        // Retorna la entidad Location con los datos actualizados
        return new Location(
            id: $command->getId(),
            ubicacion: $command->getUbicacion(),
            activoStatus: $command->getActivo(),
            idRestaurante: $command->getIdRestaurante(),
        );
    }
    public function delete(int $id): bool
    {
        $delete = DB::table('ubicaciones')
            ->where('id', $id)
            ->update(['deleted_at' => now()]);

        return $delete > 0;
    }
    public function findById(int $id): Location
    {
        $results = DB::table('ubicaciones')
            ->where('ubicaciones.id', $id)
            ->select('ubicaciones.*')
            ->first();

        return new Location(
            id: $results->id,
            ubicacion: $results->ubicacion,
            activoStatus: $results->activo,
            idRestaurante: $results->id_restaurante,
        );
    }
}
