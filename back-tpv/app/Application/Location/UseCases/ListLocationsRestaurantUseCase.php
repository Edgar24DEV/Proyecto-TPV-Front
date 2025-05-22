<?php
namespace App\Application\Location\UseCases;
use App\Application\Location\DTO\ListLocationsRestaurantCommand;
use App\Domain\Restaurant\Services\LocationService;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentTableRepository;
use App\Infrastructure\Repositories\EloquentLocationRepository;
use Illuminate\Support\Collection;
use function PHPUnit\Framework\isNan;

final class ListLocationsRestaurantUseCase
{



    public function __construct(
        private readonly EloquentRestaurantRepository $restaurantRepository,
        private readonly EloquentLocationRepository $locationRepository,
        private readonly LocationService $locationService,
    ) {
    }
    public function __invoke(ListLocationsRestaurantCommand $command): Collection
    {
        $idRestaurant = $command->getIdRestaurant();
        $this->validateOrFail($idRestaurant);
        $locations = $this->locationRepository->find($idRestaurant);
        $locations = $this->locationService->showLocationInfo($locations);
        return $locations;
    }


    private function validateOrFail(int $idRestaurant): void
    {
        if ($idRestaurant <= 0) {
            throw new \InvalidArgumentException("ID inválido");
        }

        if (!$this->restaurantRepository->exist($idRestaurant)) {
            throw new \InvalidArgumentException("ID No existe");
        }

    }

}