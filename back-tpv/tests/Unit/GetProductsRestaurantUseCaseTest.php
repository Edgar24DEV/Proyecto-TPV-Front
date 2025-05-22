<?php

namespace Tests\Unit\Application\Product\UseCases;

use App\Application\Product\DTO\GetProductRestaurantCommand;
use App\Application\Product\DTO\UpdateProductRestaurantCommand;
use App\Application\Product\UseCases\GetProductsRestaurantUseCase;
use App\Domain\Product\Entities\Product;
use App\Domain\Product\Services\ProductService;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentProductRepository;
use PHPUnit\Framework\TestCase;

class GetProductsRestaurantUseCaseTest extends TestCase
{

    public function test_get_product_restaurant_successfully()
    {
        // Arrange
        $command = new GetProductRestaurantCommand(
            idProducto: 1,
            idRestaurante: 1
        );

        // Mocks
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $productRepo = $this->createMock(EloquentProductRepository::class);
        $productService = $this->createMock(ProductService::class);

        // Mock product data (Debe ser de tipo UpdateProductRestaurantCommand)
        $product = new UpdateProductRestaurantCommand(
            activo: true,
            idProducto: 1,
            idRestaurante: 1
        );

        // Expectations
        $restaurantRepo->method('exist')->with($command->getIdRestaurante())->willReturn(true);
        $productRepo->method('exist')->with($command->getIdProducto())->willReturn(true);
        $productRepo->method('getProductRestaurant')->with($command)->willReturn($product); // Tipo correcto

        // Caso de uso
        $useCase = new GetProductsRestaurantUseCase($restaurantRepo, $productRepo, $productService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(UpdateProductRestaurantCommand::class, $result);
        $this->assertEquals(1, $result->getIdProducto());
        $this->assertEquals(1, $result->getIdRestaurante());
        $this->assertTrue($result->getActivo());
    }

    public function test_get_product_restaurant_fails_when_restaurant_not_found()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("El restaurante especificado no existe");

        $command = new GetProductRestaurantCommand(
            idProducto: 1,
            idRestaurante: 999 // Restaurante inexistente
        );

        // Mocks
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $productRepo = $this->createMock(EloquentProductRepository::class);
        $productService = $this->createMock(ProductService::class);

        // Expectations
        $restaurantRepo->method('exist')->with($command->getIdRestaurante())->willReturn(false);

        // Caso de uso
        $useCase = new GetProductsRestaurantUseCase($restaurantRepo, $productRepo, $productService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_get_product_restaurant_fails_when_product_not_found()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("El producto no existe");

        $command = new GetProductRestaurantCommand(
            idProducto: 999, // Producto inexistente
            idRestaurante: 1
        );

        // Mocks
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $productRepo = $this->createMock(EloquentProductRepository::class);
        $productService = $this->createMock(ProductService::class);

        // Expectations
        $restaurantRepo->method('exist')->with($command->getIdRestaurante())->willReturn(true);
        $productRepo->method('exist')->with($command->getIdProducto())->willReturn(false);

        // Caso de uso
        $useCase = new GetProductsRestaurantUseCase($restaurantRepo, $productRepo, $productService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
