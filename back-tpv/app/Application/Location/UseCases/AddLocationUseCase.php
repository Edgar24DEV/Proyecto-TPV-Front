<?php

namespace App\Application\Location\UseCases;

use App\Application\Location\DTO\AddLocationCommand;
use App\Domain\Restaurant\Entities\Location;
use App\Domain\Restaurant\Services\LocationService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentLocationRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;

class AddLocationUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentLocationRepository $locationRepository,
        private readonly EloquentRestaurantRepository $restaurantRepository,
        private readonly EloquentRoleRepository $rolRepository,
        private readonly EloquentCompanyRepository $companyRepository,
        private readonly LocationService $locationService,
    ) {
    }
    public function __invoke(AddLocationCommand $command): Location
    {

        $this->validateOrFail(
            $command->getIdRestaurante(),
            $command->getUbicacion()
        );

        try {
            $location = $this->locationRepository->create($command);
            $locationInfo = $this->locationService->showLocationInfoSimple($location);
        } catch (\Exception $e) {
            $locationVacio = new Location(
                id: -1,
                ubicacion: "",
                activoStatus: false,
                idRestaurante: -1,
            );
            return $locationVacio;
        }
        return $locationInfo;
    }


    private function validateOrFail($idRestaurante, $ubicacion): void
    {
        if ($idRestaurante <= 0 || !$this->restaurantRepository->exist($idRestaurante)) {
            throw new \InvalidArgumentException("ID empresa inválido");
        }
        if ($this->locationRepository->existLocationRestaurant($ubicacion, $idRestaurante) || !$ubicacion) {
            throw new \InvalidArgumentException("Ya existe la ubicacion en ese restaurante");
        }
    }
}