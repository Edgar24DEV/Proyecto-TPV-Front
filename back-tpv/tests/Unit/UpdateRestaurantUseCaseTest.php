<?php

namespace Tests\Unit\Application\Restaurant\UseCases;

use App\Application\Restaurant\DTO\UpdateRestaurantCommand;
use App\Application\Restaurant\UseCases\UpdateRestaurantUseCase;
use App\Domain\Restaurant\Entities\Restaurant;
use App\Domain\Restaurant\Services\RestaurantService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\TestCase;

class UpdateRestaurantUseCaseTest extends TestCase
{
    public function test_update_restaurant_successfully()
    {
        // Arrange
        $command = new UpdateRestaurantCommand(
            id: 1,
            idEmpresa: 1,
            nombre: "Restaurante Actualizado",
            direccion: "Nueva Dirección",
            telefono: "987654321",
            contrasenya: "newpassword",
            direccionFiscal: "Nueva Fiscal",
            cif: "B98765432",
            razonSocial: "Nuevo Nombre S.L."
        );

        // Mocks
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $restaurantService = $this->createMock(RestaurantService::class);

        // Mock restaurant data
        $updatedRestaurant = new Restaurant(
            id: 1,
            nombre: "Restaurante Actualizado",
            direccion: "Nueva Dirección",
            telefono: "987654321",
            contrasenya: "newpassword",
            direccionFiscal: "Nueva Fiscal",
            cif: "B98765432",
            razonSocial: "Nuevo Nombre S.L.",
            idEmpresa: 1
        );

        $restaurantInfo = new Restaurant(
            id: 1,
            nombre: "Restaurante Actualizado Info",
            direccion: "Nueva Dirección",
            telefono: "987654321",
            contrasenya: "newpassword",
            direccionFiscal: "Nueva Fiscal",
            cif: "B98765432",
            razonSocial: "Nuevo Nombre S.L.",
            idEmpresa: 1
        );

        // Expectations
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(true);
        $restaurantRepo->method('exist')->with($command->getId())->willReturn(true);
        $restaurantRepo->method('existwithCompany')->with($command->getId(), $command->getIdEmpresa())->willReturn(true);
        $restaurantRepo->method('update')->with($command)->willReturn($updatedRestaurant);
        $restaurantService->method('showRestaurantInfoSimple')->with($updatedRestaurant)->willReturn($restaurantInfo);

        // Caso de uso
        $useCase = new UpdateRestaurantUseCase($restaurantRepo, $roleRepo, $companyRepo, $restaurantService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Restaurant::class, $result);
        $this->assertEquals("Restaurante Actualizado Info", $result->nombre);
    }

    public function test_update_restaurant_fails_when_invalid_company_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID empresa inválido");

        $command = new UpdateRestaurantCommand(
            id: 1,
            idEmpresa: 0, // ID inválido
            nombre: "Restaurante Actualizado",
            direccion: "Nueva Dirección",
            telefono: "987654321",
            contrasenya: "newpassword",
            direccionFiscal: "Nueva Fiscal",
            cif: "B98765432",
            razonSocial: "Nuevo Nombre S.L."
        );

        // Mocks
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $restaurantService = $this->createMock(RestaurantService::class);

        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(false);

        // Caso de uso
        $useCase = new UpdateRestaurantUseCase($restaurantRepo, $roleRepo, $companyRepo, $restaurantService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_update_restaurant_fails_when_invalid_restaurant_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID restuarante inválido");

        $command = new UpdateRestaurantCommand(
            id: 0, // ID inválido
            idEmpresa: 1,
            nombre: "Restaurante Actualizado",
            direccion: "Nueva Dirección",
            telefono: "987654321",
            contrasenya: "newpassword",
            direccionFiscal: "Nueva Fiscal",
            cif: "B98765432",
            razonSocial: "Nuevo Nombre S.L."
        );

        // Mocks
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $restaurantService = $this->createMock(RestaurantService::class);

        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(true);
        $restaurantRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new UpdateRestaurantUseCase($restaurantRepo, $roleRepo, $companyRepo, $restaurantService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
