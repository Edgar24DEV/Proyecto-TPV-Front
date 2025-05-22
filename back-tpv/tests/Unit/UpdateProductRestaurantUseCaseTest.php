<?php

namespace Tests\Unit\Application\Product\UseCases;

use App\Application\Product\DTO\UpdateProductRestaurantCommand;
use App\Application\Product\UseCases\UpdateProductRestaurantUseCase;
use App\Domain\Product\Entities\Product;
use App\Infrastructure\Repositories\EloquentProductRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use PHPUnit\Framework\TestCase;

class UpdateProductRestaurantUseCaseTest extends TestCase
{
    public function test_update_product_restaurant_successfully()
    {
        // Arrange
        $command = new UpdateProductRestaurantCommand(
            activo: true,
            idProducto: 1,
            idRestaurante: 1
        );

        // Mocks
        $productRepo = $this->createMock(EloquentProductRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);

        // Mock product data
        $updatedProduct = new Product(
            id: 1,
            nombre: "Producto Actualizado",
            precio: 30.99,
            imagen: "imagen_producto.jpg",
            activo: true,
            iva: 12.0,
            idCategoria: 2,
            idEmpresa: 1
        );

        // Expectations
        $restaurantRepo->method('exist')->with($command->getIdRestaurante())->willReturn(true);
        $productRepo->method('exist')->with($command->getIdProducto())->willReturn(true);
        $productRepo->method('updateProductRestaurant')->with($command)->willReturn($updatedProduct);

        // Caso de uso
        $useCase = new UpdateProductRestaurantUseCase($productRepo, $restaurantRepo);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Product::class, $result);
        $this->assertTrue($result->activo);
    }

    public function test_update_product_restaurant_fails_when_restaurant_not_found()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("El restaurante especificado no existe");

        $command = new UpdateProductRestaurantCommand(
            activo: true,
            idProducto: 1,
            idRestaurante: 999 // Restaurante inexistente
        );

        // Mocks
        $productRepo = $this->createMock(EloquentProductRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);

        // Expectations
        $restaurantRepo->method('exist')->with($command->getIdRestaurante())->willReturn(false);

        // Caso de uso
        $useCase = new UpdateProductRestaurantUseCase($productRepo, $restaurantRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_update_product_restaurant_fails_when_product_not_found()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("El producto no existe");

        $command = new UpdateProductRestaurantCommand(
            activo: true,
            idProducto: 999, // Producto inexistente
            idRestaurante: 1
        );

        // Mocks
        $productRepo = $this->createMock(EloquentProductRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);

        // Expectations
        $restaurantRepo->method('exist')->with($command->getIdRestaurante())->willReturn(true);
        $productRepo->method('exist')->with($command->getIdProducto())->willReturn(false);

        // Caso de uso
        $useCase = new UpdateProductRestaurantUseCase($productRepo, $restaurantRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_update_product_restaurant_fails_when_product_id_is_null()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("El producto es obligatorio");

        $command = new UpdateProductRestaurantCommand(
            activo: true,
            idProducto: null, // ID inválido
            idRestaurante: 1
        );

        // Mocks
        $productRepo = $this->createMock(EloquentProductRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);

        // Caso de uso
        $useCase = new UpdateProductRestaurantUseCase($productRepo, $restaurantRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
