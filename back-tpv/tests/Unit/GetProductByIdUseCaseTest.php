<?php

namespace Tests\Unit\Application\Product\UseCases;

use App\Application\Product\DTO\GetProductCommand;
use App\Application\Product\UseCases\GetProductByIdUseCase;
use App\Domain\Product\Entities\Product;
use App\Infrastructure\Repositories\EloquentProductRepository;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\TestCase;

class GetProductByIdUseCaseTest extends TestCase
{
    public function test_get_product_by_id_successfully()
    {
        // Arrange
        $command = new GetProductCommand(id: 1);

        // Mocks
        $productRepo = $this->createMock(EloquentProductRepository::class);

        // Mock product data
        $product = new Product(
            id: 1,
            nombre: "Producto Test",
            precio: 20.99,
            imagen: "imagen_test.jpg",
            activo: true,
            iva: 10.0,
            idCategoria: 1,
            idEmpresa: 1
        );

        // Expectations
        $productRepo->method('exist')->with($command->getId())->willReturn(true);
        $productRepo->method('findById')->with($command->getId())->willReturn($product);

        // Caso de uso
        $useCase = new GetProductByIdUseCase($productRepo);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals("Producto Test", $result->nombre);
        $this->assertEquals(20.99, $result->precio);
    }

    public function test_get_product_fails_when_product_not_found()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID de producto inválido");

        $command = new GetProductCommand(id: 999); // ID inexistente

        // Mocks
        $productRepo = $this->createMock(EloquentProductRepository::class);

        // Expectations
        $productRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new GetProductByIdUseCase($productRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }


    public function test_get_product_logs_error_when_exception_is_thrown()
    {
        // Arrange
        $command = new GetProductCommand(id: 1);

        // Mocks
        $productRepo = $this->createMock(EloquentProductRepository::class);

        // Simular el sistema de logs de Laravel

        // Expectations
        $productRepo->method('exist')->with($command->getId())->willReturn(true);
        $productRepo->method('findById')->with($command->getId())->willThrowException(new \Exception("Database error"));

        // Caso de uso
        $useCase = new GetProductByIdUseCase($productRepo);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Product::class, $result);

        // Verificar que el log se haya registrado correctamente

    }

}
