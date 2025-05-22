<?php

namespace App\Application\Location\UseCases;

use App\Application\Location\DTO\UpdateLocationCommand;
use App\Domain\Restaurant\Entities\Location;
use App\Domain\Restaurant\Services\LocationService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentLocationRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;

class UpdateLocationUseCase
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
    public function __invoke(UpdateLocationCommand $command): Location
    {
        $this->validateOrFail(
            $command->getId(),
            $command->getIdRestaurante(),
        );

        // $Location = $this->LocationService->requestLocation($command);
        try {
            $location = $this->locationRepository->update($command);
            $locationInfo = $this->locationService->showLocationInfoSimple($location);
        } catch (\Exception $e) {
            $locationVacio = new Location(
                id: -1,
                ubicacion: "",
                activoStatus: 0,
                idRestaurante: -1,
            );
            return $locationVacio;
        }
        return $locationInfo;
    }


    private function validateOrFail(int $idLocation, int $idRestaurante): void
    {
        if ($idLocation <= 0 || !$this->locationRepository->exist($idLocation)) {
            throw new \InvalidArgumentException("ID ubicacion inválido");
        }
        if ($idRestaurante <= 0 || !$this->locationRepository->existLocationRestaurantByID($idLocation,$idRestaurante)) {
            throw new \InvalidArgumentException("Ubicacion no pertenece al restaurante especificado");
        }
    }
}