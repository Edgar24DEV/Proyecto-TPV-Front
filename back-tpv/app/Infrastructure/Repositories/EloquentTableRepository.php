<?php

namespace App\Infrastructure\Repositories;

use App\Application\Table\DTO\AddTableCommand;
use App\Application\Table\DTO\LoginTableCommand;
use App\Application\Table\DTO\UpdateActiveTableCommand;
use App\Application\Table\DTO\UpdateTableCommand;
use App\Domain\Restaurant\Repositories\TableRepositoryInterface;
use App\Domain\Restaurant\Entities\Table;
use Illuminate\Support\Facades\DB;

class EloquentTableRepository implements TableRepositoryInterface
{
    public function exist(int $idMesa): bool
    {
        return DB::table('mesas')
            ->where('id', $idMesa)
            ->whereNull('deleted_at')
            ->exists();
    }

    public function existTable(int $idUbicacion, string $mesa): bool
    {
        return DB::table('mesas')
            ->where('id_ubicacion', $idUbicacion)
            ->where('mesa', $mesa)
            ->whereNull('deleted_at')
            ->exists();
    }

    public function find(int $restaurant_id): array
    {
        $results = DB::table('mesas')
            ->join('ubicaciones', 'ubicaciones.id', '=', 'mesas.id_ubicacion')
            ->where('ubicaciones.id_restaurante', $restaurant_id)
            ->whereNull('ubicaciones.deleted_at')
            ->whereNull('mesas.deleted_at')
            ->select('mesas.*')
            ->get();

        return $results->map(function ($row) {
            return new Table(
                id: $row->id,
                mesa: $row->mesa,
                activo: $row->activo,
                idUbicacion: $row->id_ubicacion,
                posX: $row->pos_x,
                posY: $row->pos_y
            );
        })->toArray();
    }

    public function create(AddTableCommand $command): Table
    {
        $id = DB::table('mesas')->insertGetId([
            'mesa' => $command->getMesa(),
            'activo' => true,
            'id_ubicacion' => $command->getIdUbicacion(),
            'pos_x' => $command->getPosX() ?? 0,
            'pos_y' => $command->getPosY() ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return new Table(
            id: $id,
            mesa: $command->getMesa(),
            activo: true,
            idUbicacion: $command->getIdUbicacion(),
            posX: $command->getPosX(),
            posY: $command->getPosY()
        );
    }

    public function update(UpdateTableCommand $table): Table
    {

        $mesa = DB::table('mesas')
            ->where('id', $table->getId())
            ->whereNull('deleted_at')
            ->first();

        DB::table('mesas')
            ->where('id', $table->getId())
            ->update([
                'mesa' => $table->getMesa(),
                'activo' => $table->getActivo(),
                'id_ubicacion' => $table->getIdUbicacion(),
                'pos_x' => $table->getPosX() ?? $mesa->pos_x,
                'pos_y' => $table->getPosY() ?? $mesa->pos_y,
                'updated_at' => now(),
            ]);

        return new Table(
            id: $table->getId(),
            mesa: $table->getMesa(),
            activo: $table->getActivo(),
            idUbicacion: $table->getIdUbicacion(),
            posX: $table->getPosX(),
            posY: $table->getPosY()
        );
    }

    public function updateActive(UpdateActiveTableCommand $table): Table
    {
        DB::table('mesas')
            ->where('id', $table->getId())
            ->update([
                'activo' => $table->getActivo(),
                'updated_at' => now(),
            ]);

        return new Table(
            id: $table->getId(),
            mesa: $table->getMesa(),
            activo: $table->getActivo(),
            idUbicacion: $table->getIdUbicacion(),
            posX: $table->getPosX(),
            posY: $table->getPosY()
        );
    }

    public function delete(int $id): bool
    {
        $delete = DB::table('mesas')
            ->where('id', $id)
            ->update(['deleted_at' => now()]);

        return $delete > 0;
    }

    public function findById(int $id): Table
    {
        $row = DB::table('mesas')
            ->where('id', $id)
            ->first();

        return new Table(
            id: $row->id,
            mesa: $row->mesa,
            activo: $row->activo,
            idUbicacion: $row->id_ubicacion,
            posX: $row->pos_x,
            posY: $row->pos_y
        );
    }
}
