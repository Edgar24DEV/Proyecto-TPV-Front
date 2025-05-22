<?php

namespace App\Domain\Restaurant\Services;

use App\Domain\Restaurant\Entities\Table;
use Illuminate\Http\Request;

class TableService
{
    public function showTableInfo($tables)
    {
        return collect($tables)->map(function ($row) {
            return new Table(
                id: $row->id,
                mesa: $row->mesa,
                activo: $row->activo,
                idUbicacion: $row->idUbicacion,
                posX: $row->posX,
                posY: $row->posY
            );
        });
    }

    public function showTableInfoSimple($table)
    {
        return new Table(
            id: $table->id,
            mesa: $table->mesa,
            activo: $table->activo,
            idUbicacion: $table->idUbicacion,
            posX: $table->posX,
            posY: $table->posY
        );
    }
}
