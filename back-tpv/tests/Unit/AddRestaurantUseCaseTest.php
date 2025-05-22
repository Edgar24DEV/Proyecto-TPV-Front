<?php

namespace Tests\Unit\Application\Restaurant\UseCases;

use App\Application\Restaurant\DTO\AddRestaurantCommand;
use App\Application\Restaurant\UseCases\AddRestaurantUseCase;
use App\Domain\Restaurant\Entities\Restaurant;
use App\Domain\Restaurant\Services\RestaurantService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use PHPUnit\Framework\TestCase;

class AddRestaurantUseCaseTest extends TestCase
{
    public function test_add_restaurant_successfully()
    {
        // Arrange
        $command = new AddRestaurantCommand(
            nombre: "Nuevo Restaurante",
            direccion: "Calle Falsa 123",
            telefono: "123456789",
            contrasenya: "securepassword",
            direccionFiscal: "Fiscal Address",
            cif: "B12345678",
            razonSocial: "Mi Restaurante S.L.",
            idEmpresa: 1
        );


        // Mocks
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $restaurantService = $this->createMock(RestaurantService::class);

        // Mock restaurant data
        $createdRestaurant = new Restaurant(
            id: 1,
            nombre: "Nuevo Restaurante",
            direccion: "Calle Falsa 123",
            telefono: "123456789",
            contrasenya: "securepassword",
            direccionFiscal: "Fiscal Address",
            cif: "B12345678",
            razonSocial: "Mi Restaurante S.L.",
            idEmpresa: 1
        );

        $restaurantInfo = new Restaurant(
            id: 1,
            nombre: "Nuevo Restaurante Info",
            direccion: "Calle Falsa 123",
            telefono: "123456789",
            contrasenya: "securepassword",
            direccionFiscal: "Fiscal Address",
            cif: "B12345678",
            razonSocial: "Mi Restaurante S.L.",
            idEmpresa: 1
        );

        // Expectations
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(true);
        $restaurantRepo->method('existName')->with($command->getNombre())->willReturn(false);
        $restaurantRepo->method('create')->with($command)->willReturn($createdRestaurant);
        $restaurantService->method('showRestaurantInfoSimple')->with($createdRestaurant)->willReturn($restaurantInfo);

        // Caso de uso
        $useCase = new AddRestaurantUseCase($restaurantRepo, $roleRepo, $companyRepo, $restaurantService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Restaurant::class, $result);
        $this->assertEquals("Nuevo Restaurante Info", $result->nombre);
    }

    public function test_add_restaurant_fails_when_invalid_company_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID empresa inválido");

        $command = new AddRestaurantCommand(
            nombre: "Nuevo Restaurante",
            direccion: "Calle Falsa 123",
            telefono: "123456789",
            contrasenya: "securepassword",
            direccionFiscal: "Fiscal Address",
            cif: "B12345678",
            razonSocial: "Mi Restaurante S.L.",
            idEmpresa: 0
        );


        // Mocks
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $restaurantService = $this->createMock(RestaurantService::class);

        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(false);

        // Caso de uso
        $useCase = new AddRestaurantUseCase($restaurantRepo, $roleRepo, $companyRepo, $restaurantService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_add_restaurant_fails_when_restaurant_already_exists()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("El restaurante ya existe");

        $command = new AddRestaurantCommand(
            nombre: "Nuevo Restaurante",
            direccion: "Calle Falsa 123",
            telefono: "123456789",
            contrasenya: "securepassword",
            direccionFiscal: "Fiscal Address",
            cif: "B12345678",
            razonSocial: "Mi Restaurante S.L.",
            idEmpresa: 1
        );


        // Mocks
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $restaurantService = $this->createMock(RestaurantService::class);

        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(true);
        $restaurantRepo->method('existName')->with($command->getNombre())->willReturn(true);

        // Caso de uso
        $useCase = new AddRestaurantUseCase($restaurantRepo, $roleRepo, $companyRepo, $restaurantService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
