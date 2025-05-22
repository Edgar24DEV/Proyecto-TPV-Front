<?php

namespace Tests\Unit\Application\Product\UseCases;

use App\Application\Product\DTO\UpdateProductCommand;
use App\Application\Product\UseCases\UpdateProductUseCase;
use App\Domain\Product\Entities\Product;
use App\Infrastructure\Repositories\EloquentProductRepository;
use App\Domain\Product\Services\ProductService;
use PHPUnit\Framework\TestCase;

class UpdateProductUseCaseTest extends TestCase
{
    public function test_update_product_successfully()
    {
        // Arrange
        $command = new UpdateProductCommand(
            id: 1,
            nombre: "Producto Actualizado",
            precio: 29.99,
            imagen: "nueva_imagen.jpg",
            activo: true,
            idCategoria: 2,
            iva: 15.0
        );

        // Mocks
        $productRepo = $this->createMock(EloquentProductRepository::class);
        $productService = $this->createMock(ProductService::class);

        // Mock product data
        $updatedProduct = new Product(
            id: 1,
            nombre: "Producto Actualizado",
            precio: 29.99,
            imagen: "nueva_imagen.jpg",
            activo: true,
            iva: 15.0,
            idCategoria: 2,
            idEmpresa: 1
        );

        // Expectations
        $productRepo->method('exist')->with($command->getId())->willReturn(true);
        $productRepo->method('update')->with($command)->willReturn($updatedProduct);

        // Caso de uso
        $useCase = new UpdateProductUseCase($productRepo, $productService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals("Producto Actualizado", $result->getNombre());
        $this->assertEquals(29.99, $result->getPrecio());
    }

    public function test_update_product_fails_when_product_not_found()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID de producto inválido");

        $command = new UpdateProductCommand(
            id: 999, // Producto inexistente
            nombre: "Producto",
            precio: 29.99,
            imagen: "imagen.jpg",
            activo: true,
            idCategoria: 2,
            iva: 15.0
        );

        // Mocks
        $productRepo = $this->createMock(EloquentProductRepository::class);
        $productService = $this->createMock(ProductService::class);

        // Expectations
        $productRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new UpdateProductUseCase($productRepo, $productService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_update_product_handles_exception_and_returns_empty_product()
    {
        // Arrange
        $command = new UpdateProductCommand(
            id: 1,
            nombre: "Producto",
            precio: 29.99,
            imagen: "imagen.jpg",
            activo: true,
            idCategoria: 2,
            iva: 15.0
        );

        // Mocks
        $productRepo = $this->createMock(EloquentProductRepository::class);
        $productService = $this->createMock(ProductService::class);

        // Expectations
        $productRepo->method('exist')->with($command->getId())->willReturn(true);
        $productRepo->method('update')->with($command)->willThrowException(new \Exception("Database error"));

        // Caso de uso
        $useCase = new UpdateProductUseCase($productRepo, $productService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals(-1, $result->id);
        $this->assertEmpty($result->getNombre());
        $this->assertFalse($result->getActivo());
    }
}
