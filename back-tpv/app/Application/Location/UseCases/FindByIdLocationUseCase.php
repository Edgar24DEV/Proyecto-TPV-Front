<?php
namespace App\Application\Location\UseCases;
use App\Application\Location\DTO\FindByIdLocationCommand;
use App\Domain\Restaurant\Entities\Location;
use App\Domain\Restaurant\Services\LocationService;
use App\Infrastructure\Repositories\EloquentLocationRepository;
use function PHPUnit\Framework\isNan;

final class FindByIdLocationUseCase
{



    public function __construct(
        private readonly EloquentLocationRepository $locationRepository,
        private readonly LocationService $locationService
    ) {
    }
    public function __invoke(FindByIdLocationCommand $command): Location
    {
        $this->validateOrFail($command->getId());
        $location = $this->locationRepository->findById($command->getId());
        $locationInfo = $this->locationService->showLocationInfoSimple($location);
        return $locationInfo;
    }


    private function validateOrFail(int $id): void
    {
        if ($id <= 0 || !$this->locationRepository->exist($id)) {
            throw new \InvalidArgumentException("ID ubicacion inválido");
        }
    }
}