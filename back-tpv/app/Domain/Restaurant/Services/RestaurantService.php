<?php
namespace App\Domain\Restaurant\Services;

use App\Domain\Restaurant\Entities\Restaurant;
use Illuminate\Support\Collection;

class RestaurantService
{

    public function showRestaurantInfo(iterable $rawRestaurants): Collection
    {
        return collect($rawRestaurants)->map(function ($row) {
            return new Restaurant(
                id: $row->id ?? null,
                nombre: $row->nombre ?? null,
                direccion: $row->direccion ?? null,
                telefono: $row->telefono ?? null,
                contrasenya: null,
                direccionFiscal: $row->direccionFiscal ?? null,
                cif: $row->cif ?? null ,
                razonSocial: $row->razonSocial ?? null,
                idEmpresa: $row->idEmpresa ?? null,
            );
        });
    }

    public function showRestaurantInfoSimple($restaurant): Restaurant
    {
        return new Restaurant(
            id: $restaurant->id,
            nombre: $restaurant->nombre,
            direccion: $restaurant->direccion,
            telefono: $restaurant->telefono,
            contrasenya: null,
            direccionFiscal: $restaurant->direccionFiscal ?? null,
            cif: $restaurant->cif,
            razonSocial: $restaurant->razonSocial ?? null,
            idEmpresa: $restaurant->idEmpresa ?? null,
        );
    }
}
