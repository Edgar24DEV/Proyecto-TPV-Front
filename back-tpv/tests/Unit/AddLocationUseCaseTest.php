<?php

namespace Tests\Unit\Application\Location\UseCases;

use App\Application\Location\DTO\AddLocationCommand;
use App\Application\Location\UseCases\AddLocationUseCase;
use App\Domain\Restaurant\Entities\Location;
use App\Domain\Restaurant\Services\LocationService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentLocationRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use PHPUnit\Framework\TestCase;

class AddLocationUseCaseTest extends TestCase
{
    public function test_add_location_successfully()
    {
        // Arrange
        $command = new AddLocationCommand(ubicacion: "Terraza", idRestaurante: 1);

        // Mocks
        $locationRepo = $this->createMock(EloquentLocationRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $locationService = $this->createMock(LocationService::class);

        // Mock created location
        $newLocation = new Location(
            id: 1,
            ubicacion: "Terraza",
            activoStatus: true,
            idRestaurante: 1
        );

        // Expectations
        $restaurantRepo->method('exist')->with($command->getIdRestaurante())->willReturn(true);
        $locationRepo->method('existLocationRestaurant')->with($command->getUbicacion(), $command->getIdRestaurante())->willReturn(false);
        $locationRepo->method('create')->with($command)->willReturn($newLocation);
        $locationService->method('showLocationInfoSimple')->with($newLocation)->willReturn($newLocation);

        // Caso de uso
        $useCase = new AddLocationUseCase($locationRepo, $restaurantRepo, $roleRepo, $companyRepo, $locationService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Location::class, $result);
        $this->assertEquals("Terraza", $result->ubicacion);
        $this->assertEquals(1, $result->idRestaurante);
    }

    public function test_add_location_fails_when_invalid_restaurant_id()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID empresa inválido");

        $command = new AddLocationCommand(ubicacion: "Barra", idRestaurante: -1); // ID inválido

        // Mocks
        $locationRepo = $this->createMock(EloquentLocationRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $locationService = $this->createMock(LocationService::class);

        // Expectations
        $restaurantRepo->method('exist')->with($command->getIdRestaurante())->willReturn(false);

        // Caso de uso
        $useCase = new AddLocationUseCase($locationRepo, $restaurantRepo, $roleRepo, $companyRepo, $locationService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_add_location_fails_when_location_already_exists()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Ya existe la ubicacion en ese restaurante");

        $command = new AddLocationCommand(ubicacion: "Salón", idRestaurante: 1);

        // Mocks
        $locationRepo = $this->createMock(EloquentLocationRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $locationService = $this->createMock(LocationService::class);

        // Expectations
        $restaurantRepo->method('exist')->with($command->getIdRestaurante())->willReturn(true);
        $locationRepo->method('existLocationRestaurant')->with($command->getUbicacion(), $command->getIdRestaurante())->willReturn(true);

        // Caso de uso
        $useCase = new AddLocationUseCase($locationRepo, $restaurantRepo, $roleRepo, $companyRepo, $locationService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
