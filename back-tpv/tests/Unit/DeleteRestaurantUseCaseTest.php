<?php

namespace Tests\Unit\Application\Restaurant\UseCases;

use App\Application\Restaurant\DTO\DeleteRestaurantCommand;
use App\Application\Restaurant\UseCases\DeleteRestaurantUseCase;
use App\Domain\Restaurant\Entities\Restaurant;
use App\Domain\Restaurant\Services\RestaurantService;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use PHPUnit\Framework\TestCase;

class DeleteRestaurantUseCaseTest extends TestCase
{
    public function test_delete_restaurant_successfully()
    {
        // Arrange
        $command = new DeleteRestaurantCommand(id: 1);

        // Mocks
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $restaurantService = $this->createMock(RestaurantService::class);

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

        // Expectations
        $restaurantRepo->method('find')->with($command->getId())->willReturn($restaurant);
        $restaurantRepo->method('softDelete')->with($command->getId())->willReturn(true);

        // Caso de uso
        $useCase = new DeleteRestaurantUseCase($restaurantRepo, $restaurantService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertTrue($result);
    }

    public function test_delete_restaurant_fails_when_not_found()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Restaurante no encontrado.");

        $command = new DeleteRestaurantCommand(id: 999); // ID inexistente

        // Mocks
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $restaurantService = $this->createMock(RestaurantService::class);

        // Hacemos que el método 'find' lance la excepción directamente
        $restaurantRepo->method('find')->willThrowException(new \Exception("Restaurante no encontrado."));

        // Caso de uso
        $useCase = new DeleteRestaurantUseCase($restaurantRepo, $restaurantService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }


}
