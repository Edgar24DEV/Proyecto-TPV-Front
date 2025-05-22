<?php

namespace Tests\Unit\Application\Location\UseCases;

use App\Application\Location\DTO\ListLocationsRestaurantCommand;
use App\Application\Location\UseCases\ListLocationsRestaurantUseCase;
use App\Domain\Restaurant\Entities\Location;
use App\Domain\Restaurant\Services\LocationService;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentLocationRepository;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class ListLocationsRestaurantUseCaseTest extends TestCase
{
    public function test_list_locations_successfully()
    {
        // Arrange
        $command = new ListLocationsRestaurantCommand(idRestaurante: 1);

        // Mocks
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $locationRepo = $this->createMock(EloquentLocationRepository::class);
        $locationService = $this->createMock(LocationService::class);

        // Mock locations data
        $locations = [
            new Location(id: 1, ubicacion: "Terraza", activoStatus: true, idRestaurante: 1),
            new Location(id: 2, ubicacion: "Salón", activoStatus: true, idRestaurante: 1),
        ];

        $processedLocations = new Collection([
            new Location(id: 1, ubicacion: "Terraza - procesada", activoStatus: true, idRestaurante: 1),
            new Location(id: 2, ubicacion: "Salón - procesada", activoStatus: true, idRestaurante: 1),
        ]);

        // Expectations
        $restaurantRepo->method('exist')->with($command->getIdRestaurant())->willReturn(true);
        $locationRepo->method('find')->with($command->getIdRestaurant())->willReturn($locations);
        $locationService->method('showLocationInfo')->with($locations)->willReturn($processedLocations);

        // Caso de uso
        $useCase = new ListLocationsRestaurantUseCase($restaurantRepo, $locationRepo, $locationService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertEquals("Terraza - procesada", $result[0]->ubicacion);
        $this->assertEquals("Salón - procesada", $result[1]->ubicacion);
    }

    public function test_list_locations_fails_when_invalid_restaurant_id()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID No existe");

        $command = new ListLocationsRestaurantCommand(idRestaurante: 999); // ID inválido

        // Mocks
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $locationRepo = $this->createMock(EloquentLocationRepository::class);
        $locationService = $this->createMock(LocationService::class);

        // Expectations
        $restaurantRepo->method('exist')->with($command->getIdRestaurant())->willReturn(false);

        // Caso de uso
        $useCase = new ListLocationsRestaurantUseCase($restaurantRepo, $locationRepo, $locationService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
