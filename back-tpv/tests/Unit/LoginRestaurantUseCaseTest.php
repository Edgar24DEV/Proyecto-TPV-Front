<?php

namespace Tests\Unit\Application\Restaurant\UseCases;

use App\Application\Restaurant\DTO\LoginRestaurantCommand;
use App\Application\Restaurant\UseCases\LoginRestaurantUseCase;
use App\Domain\Restaurant\Entities\Restaurant;
use App\Domain\Restaurant\Services\RestaurantService;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\TestCase;

class LoginRestaurantUseCaseTest extends TestCase
{
    public function test_login_restaurant_successfully()
    {
        // Arrange
        $command = new LoginRestaurantCommand(
            nombre: "Restaurante Test",
            contrasenya: "securepassword"
        );

        // Mocks
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $restaurantService = $this->createMock(RestaurantService::class);
        Log::shouldReceive('channel')->with('restaurant')->andReturnSelf();

        // Mock restaurant data
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
        $restaurantRepo->method('login')->with($command)->willReturn($restaurant);
        $restaurantService->method('showRestaurantInfoSimple')->with($restaurant)->willReturn($restaurantInfo);

        // Caso de uso
        $useCase = new LoginRestaurantUseCase($restaurantRepo, $restaurantService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Restaurant::class, $result);
        $this->assertEquals("Restaurante Test Info", $result->nombre);
    }

    public function test_login_restaurant_fails_when_restaurant_not_found()
    {
        // Arrange
        $command = new LoginRestaurantCommand(
            nombre: "Restaurante Inexistente",
            contrasenya: "wrongpassword"
        );

        // Mocks
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $restaurantService = $this->createMock(RestaurantService::class);
        Log::shouldReceive('channel')->with('restaurant')->andReturnSelf();

        // Expectations
        $restaurantRepo->method('login')->with($command)->willThrowException(new \Exception("No se pudo encontrar el restaurante"));

        // Caso de uso
        $useCase = new LoginRestaurantUseCase($restaurantRepo, $restaurantService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Restaurant::class, $result);
        $this->assertEquals(-1, $result->id);
        $this->assertNull($result->nombre);
    }
}
