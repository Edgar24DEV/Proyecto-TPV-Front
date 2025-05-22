<?php

namespace Tests\Unit\Application\Location\UseCases;

use App\Application\Location\DTO\FindByIdLocationCommand;
use App\Application\Location\UseCases\FindByIdLocationUseCase;
use App\Domain\Restaurant\Entities\Location;
use App\Domain\Restaurant\Services\LocationService;
use App\Infrastructure\Repositories\EloquentLocationRepository;
use PHPUnit\Framework\TestCase;

class FindByIdLocationUseCaseTest extends TestCase
{
    public function test_find_location_successfully()
    {
        // Arrange
        $command = new FindByIdLocationCommand(id: 1);

        // Mocks
        $locationRepo = $this->createMock(EloquentLocationRepository::class);
        $locationService = $this->createMock(LocationService::class);

        // Mock existing location
        $location = new Location(
            id: 1,
            ubicacion: "Terraza",
            activoStatus: true,
            idRestaurante: 1
        );

        // Expectations
        $locationRepo->method('exist')->with($command->getId())->willReturn(true);
        $locationRepo->method('findById')->with($command->getId())->willReturn($location);
        $locationService->method('showLocationInfoSimple')->with($location)->willReturn($location);

        // Caso de uso
        $useCase = new FindByIdLocationUseCase($locationRepo, $locationService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Location::class, $result);
        $this->assertEquals(1, $result->id);
        $this->assertEquals("Terraza", $result->ubicacion);
    }

    public function test_find_location_fails_when_invalid_id()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID ubicacion inválido");

        $command = new FindByIdLocationCommand(id: -1); // ID inválido

        // Mocks
        $locationRepo = $this->createMock(EloquentLocationRepository::class);
        $locationService = $this->createMock(LocationService::class);

        // Expectations
        $locationRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new FindByIdLocationUseCase($locationRepo, $locationService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
