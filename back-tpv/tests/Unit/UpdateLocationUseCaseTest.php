<?php

namespace Tests\Unit\Application\Location\UseCases;

use App\Application\Location\DTO\UpdateLocationCommand;
use App\Application\Location\UseCases\UpdateLocationUseCase;
use App\Domain\Restaurant\Entities\Location;
use App\Domain\Restaurant\Services\LocationService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentLocationRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use PHPUnit\Framework\TestCase;

class UpdateLocationUseCaseTest extends TestCase
{
    public function test_update_location_successfully()
    {
        // Arrange
        $command = new UpdateLocationCommand(id: 1, ubicacion: "Terraza", activo: true, idRestaurante: 1);

        // Mocks
        $locationRepo = $this->createMock(EloquentLocationRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $locationService = $this->createMock(LocationService::class);

        // Mock updated location
        $updatedLocation = new Location(
            id: 1,
            ubicacion: "Terraza",
            activoStatus: true,
            idRestaurante: 1
        );

        // Expectations
        $locationRepo->method('exist')->with($command->getId())->willReturn(true);
        $locationRepo->method('existLocationRestaurantByID')->with($command->getId(), $command->getIdRestaurante())->willReturn(true);
        $locationRepo->method('update')->with($command)->willReturn($updatedLocation);
        $locationService->method('showLocationInfoSimple')->with($updatedLocation)->willReturn($updatedLocation);

        // Caso de uso
        $useCase = new UpdateLocationUseCase($locationRepo, $restaurantRepo, $roleRepo, $companyRepo, $locationService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Location::class, $result);
        $this->assertEquals(1, $result->id);
        $this->assertEquals("Terraza", $result->ubicacion);
        $this->assertTrue($result->activoStatus);
    }

    public function test_update_location_fails_when_invalid_location_id()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID ubicacion inválido");

        $command = new UpdateLocationCommand(id: -1, ubicacion: "Barra", activo: false, idRestaurante: 1); // ID inválido

        // Mocks
        $locationRepo = $this->createMock(EloquentLocationRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $locationService = $this->createMock(LocationService::class);

        // Expectations
        $locationRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new UpdateLocationUseCase($locationRepo, $restaurantRepo, $roleRepo, $companyRepo, $locationService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_update_location_fails_when_location_not_belongs_to_restaurant()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Ubicacion no pertenece al restaurante especificado");

        $command = new UpdateLocationCommand(id: 1, ubicacion: "Salón", activo: true, idRestaurante: 2);

        // Mocks
        $locationRepo = $this->createMock(EloquentLocationRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $locationService = $this->createMock(LocationService::class);

        // Expectations
        $locationRepo->method('exist')->with($command->getId())->willReturn(true);
        $locationRepo->method('existLocationRestaurantByID')->with($command->getId(), $command->getIdRestaurante())->willReturn(false);

        // Caso de uso
        $useCase = new UpdateLocationUseCase($locationRepo, $restaurantRepo, $roleRepo, $companyRepo, $locationService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
