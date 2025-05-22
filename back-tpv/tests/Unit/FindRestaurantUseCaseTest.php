<?php

namespace Tests\Unit\Application\Restaurant\UseCases;

use App\Application\Restaurant\DTO\FindRestaurantCommand;
use App\Application\Restaurant\UseCases\FindRestaurantUseCase;
use App\Domain\Restaurant\Entities\Restaurant;
use App\Domain\Restaurant\Services\RestaurantService;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\TestCase;

class FindRestaurantUseCaseTest extends TestCase
{
    public function test_find_restaurant_by_id_successfully()
    {
        // Arrange
        $restaurantId = 1;
        $command = new FindRestaurantCommand($restaurantId);

        // Mocks
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $restaurantService = $this->createMock(RestaurantService::class);
        Log::shouldReceive('channel')->with('restaurant')->andReturnSelf();

        $restaurant = new Restaurant(
            id: 1,
            nombre: "Restaurante Test",
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
            nombre: "Restaurante Test Info",
            direccion: "Calle Falsa 123",
            telefono: "123456789",
            contrasenya: "securepassword",
            direccionFiscal: "Fiscal Address",
            cif: "B12345678",
            razonSocial: "Mi Restaurante S.L.",
            idEmpresa: 1
        );

        // Expectations
        $restaurantRepo->method('exist')->with($restaurantId)->willReturn(true);
        $restaurantRepo->method('findById')->with($restaurantId)->willReturn($restaurant);
        $restaurantService->method('showRestaurantInfoSimple')->with($restaurant)->willReturn($restaurantInfo);

        // Caso de uso
        $useCase = new FindRestaurantUseCase($restaurantRepo, $restaurantService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Restaurant::class, $result);
        $this->assertEquals("Restaurante Test Info", $result->nombre);
    }

    public function test_find_restaurant_fails_when_restaurant_does_not_exist()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID restuarante inválido");

        $restaurantId = 999; // ID inválido
        $command = new FindRestaurantCommand($restaurantId);

        // Mocks
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $restaurantService = $this->createMock(RestaurantService::class);

        // Expectations
        $restaurantRepo->method('exist')->with($restaurantId)->willReturn(false);

        // Caso de uso
        $useCase = new FindRestaurantUseCase($restaurantRepo, $restaurantService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
