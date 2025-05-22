<?php
namespace App\Domain\Restaurant\Services;
use App\Domain\Restaurant\Entities\Location;
use Illuminate\Http\Request;
class LocationService
{

    public function showLocationInfo($locations)
    {

        return collect($locations)->map(function ($row) {
            return new Location(
                id: $row->id,
                ubicacion: $row->ubicacion,
                activoStatus: $row->activoStatus,
                idRestaurante: $row->idRestaurante
            );
        });
    }
    public function showLocationInfoSimple($location)
    {
        return new Location(
            id: $location->id,
            ubicacion: $location->ubicacion,
            activoStatus: $location->activoStatus,
            idRestaurante: $location->idRestaurante
        );
    }


}