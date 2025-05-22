<?php

namespace Tests\Unit\Application\Restaurant\UseCases;

use App\Application\Restaurant\DTO\ListRestaurantsCompanyCommand;
use App\Application\Restaurant\UseCases\ListRestaurantsCompanyUseCase;
use App\Domain\Restaurant\Entities\Restaurant;
use App\Domain\Restaurant\Services\RestaurantService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class ListRestaurantsCompanyUseCaseTest extends TestCase
{
    public function test_list_restaurants_successfully()
    {
        // Arrange
        $command = new ListRestaurantsCompanyCommand(idEmpresa: 1);

        // Mocks
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $restaurantService = $this->createMock(RestaurantService::class);

        // Mock restaurant data
        $restaurants = [
            new Restaurant(id: 1, nombre: "Restaurante A", direccion: "Calle 1", telefono: "111111111", contrasenya: "123", direccionFiscal: "Fiscal A", cif: "CIF1", razonSocial: "Restaurante SA", idEmpresa: 1),
            new Restaurant(id: 2, nombre: "Restaurante B", direccion: "Calle 2", telefono: "222222222", contrasenya: "456", direccionFiscal: "Fiscal B", cif: "CIF2", razonSocial: "Restaurante SRL", idEmpresa: 1)
        ];

        $processedRestaurants = new Collection([
            new Restaurant(id: 1, nombre: "Restaurante A Info", direccion: "Calle 1", telefono: "111111111", contrasenya: "123", direccionFiscal: "Fiscal A", cif: "CIF1", razonSocial: "Restaurante SA", idEmpresa: 1),
            new Restaurant(id: 2, nombre: "Restaurante B Info", direccion: "Calle 2", telefono: "222222222", contrasenya: "456", direccionFiscal: "Fiscal B", cif: "CIF2", razonSocial: "Restaurante SRL", idEmpresa: 1)
        ]);

        // Expectations
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(true);
        $restaurantRepo->method('findByCompanyID')->with($command->getIdEmpresa())->willReturn($restaurants);  // Devuelve un array, no una Collection
        $restaurantService->method('showRestaurantInfo')->with($restaurants)->willReturn($processedRestaurants);

        // Caso de uso
        $useCase = new ListRestaurantsCompanyUseCase($restaurantRepo, $companyRepo, $restaurantService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertEquals("Restaurante A Info", $result[0]->nombre);
        $this->assertEquals("Restaurante B Info", $result[1]->nombre);
    }

    public function test_list_restaurants_fails_when_invalid_company_id()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID No existe");

        $command = new ListRestaurantsCompanyCommand(idEmpresa: 999); // ID inválido

        // Mocks
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $restaurantService = $this->createMock(RestaurantService::class);

        // Expectations
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(false);

        // Caso de uso
        $useCase = new ListRestaurantsCompanyUseCase($restaurantRepo, $companyRepo, $restaurantService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
