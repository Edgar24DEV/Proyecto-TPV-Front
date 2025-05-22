<?php

namespace Tests\Unit\Application\Restaurant\UseCases;

use App\Application\Restaurant\DTO\AddRestaurantProductCommand;
use App\Application\Product\DTO\UpdateProductRestaurantCommand;
use App\Application\Restaurant\UseCases\AddRestaurantProductUseCase;
use App\Domain\Product\Entities\Product;
use App\Infrastructure\Repositories\EloquentProductRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use PHPUnit\Framework\TestCase;

class AddRestaurantProductUseCaseTest extends TestCase
{
    public function test_add_restaurant_product_successfully()
    {
        // Arrange
        $command = new AddRestaurantProductCommand(
            idRestaurante: 1,
            idProducto: 1,
            activo: true
        );

        // Mocks
        $productRepo = $this->createMock(EloquentProductRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);

        // Mock product data
        $existingProduct = new Product(
            id: 1,
            nombre: "Producto Test",
            precio: 10.00,
            imagen: "imagen_test.jpg",
            activo: true,
            iva: 5.00,
            idCategoria: 2,
            idEmpresa: 1
        );

        // Expectations
        $restaurantRepo->method('exist')->with($command->getIdRestaurante())->willReturn(true);
        $productRepo->method('exist')->with($command->getIdProducto())->willReturn(true);
        $productRepo->method('findByProductAndRestaurant')->with($command->getIdProducto(), $command->getIdRestaurante())->willReturn(false);
        $restaurantRepo->method('saveRestaurantProduct')->with($command)->willReturn($command);



        // Caso de uso
        $useCase = new AddRestaurantProductUseCase($productRepo, $restaurantRepo);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(AddRestaurantProductCommand::class, $result);
        $this->assertEquals("Producto Test", $existingProduct->nombre);
    }

    public function test_update_existing_restaurant_product()
    {
        // Arrange
        $command = new AddRestaurantProductCommand(
            idRestaurante: 1,
            idProducto: 1,
            activo: false
        );

        $updateCommand = new UpdateProductRestaurantCommand(
            activo: false,
            idRestaurante: 1,
            idProducto: 1
        );

        // Mocks
        $productRepo = $this->createMock(EloquentProductRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);

        // Mock product data
        $updatedProduct = new Product(
            id: 1,
            nombre: "Producto Actualizado",
            precio: 12.50,
            imagen: "imagen_actualizada.jpg",
            activo: true,
            iva: 5.00,
            idCategoria: 2,
            idEmpresa: 1
        );

        // Expectations
        $restaurantRepo->method('exist')->with($command->getIdRestaurante())->willReturn(true);
        $productRepo->method('exist')->with($command->getIdProducto())->willReturn(true);
        $productRepo->method('findByProductAndRestaurant')->with($command->getIdProducto(), $command->getIdRestaurante())->willReturn(true);
        $productRepo->method('updateProductRestaurant')->with($updateCommand)->willReturn($updatedProduct);

        // Caso de uso
        $useCase = new AddRestaurantProductUseCase($productRepo, $restaurantRepo);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals("Producto Actualizado", $result->nombre);
    }

    public function test_add_restaurant_product_fails_when_restaurant_not_exist()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("El restaurante especificado no existe");

        $command = new AddRestaurantProductCommand(
            idRestaurante: 0, // ID inválido
            idProducto: 1,
            activo: true
        );

        // Mocks
        $productRepo = $this->createMock(EloquentProductRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);

        $restaurantRepo->method('exist')->with($command->getIdRestaurante())->willReturn(false);

        // Caso de uso
        $useCase = new AddRestaurantProductUseCase($productRepo, $restaurantRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_add_restaurant_product_fails_when_product_not_exist()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Este producto no existe en la base de datos");

        $command = new AddRestaurantProductCommand(
            idRestaurante: 1,
            idProducto: 0, // ID inválido
            activo: true
        );

        // Mocks
        $productRepo = $this->createMock(EloquentProductRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);

        $restaurantRepo->method('exist')->with($command->getIdRestaurante())->willReturn(true);
        $productRepo->method('exist')->with($command->getIdProducto())->willReturn(false);

        // Caso de uso
        $useCase = new AddRestaurantProductUseCase($productRepo, $restaurantRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
