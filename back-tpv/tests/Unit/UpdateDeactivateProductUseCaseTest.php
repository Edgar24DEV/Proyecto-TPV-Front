<?php

namespace Tests\Unit\Application\Product\UseCases;

use App\Application\Product\DTO\UpdateDeactivateProductCommand;
use App\Application\Product\UseCases\UpdateDeactivateProductUseCase;
use App\Domain\Product\Entities\Product;
use App\Infrastructure\Repositories\EloquentProductRepository;
use PHPUnit\Framework\TestCase;

class UpdateDeactivateProductUseCaseTest extends TestCase
{
    public function test_update_deactivate_product_successfully()
    {
        // Arrange
        $command = new UpdateDeactivateProductCommand(
            activo: false,
            idProducto: 1
        );

        // Mocks
        $productRepo = $this->createMock(EloquentProductRepository::class);

        // Mock product data
        $updatedProduct = new Product(
            id: 1,
            nombre: "Producto Desactivado",
            precio: 20.99,
            imagen: "imagen_producto.jpg",
            activo: false,
            iva: 10.0,
            idCategoria: 1,
            idEmpresa: 1
        );

        // Expectations
        $productRepo->method('exist')->with($command->getIdProducto())->willReturn(true);
        $productRepo->method('updateProductDeactivate')->with($command)->willReturn($updatedProduct);

        // Caso de uso
        $useCase = new UpdateDeactivateProductUseCase($productRepo);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals("Producto Desactivado", $result->nombre);
        $this->assertFalse($result->activo);
    }

    public function test_update_deactivate_product_fails_when_product_not_found()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("El producto no existe");

        $command = new UpdateDeactivateProductCommand(
            activo: false,
            idProducto: 999 // Producto inexistente
        );

        // Mocks
        $productRepo = $this->createMock(EloquentProductRepository::class);

        // Expectations
        $productRepo->method('exist')->with($command->getIdProducto())->willReturn(false);

        // Caso de uso
        $useCase = new UpdateDeactivateProductUseCase($productRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_update_deactivate_product_fails_when_product_id_is_null()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("El producto es obligatorio");

        $command = new UpdateDeactivateProductCommand(
            activo: false,
            idProducto: null // ID inválido
        );

        // Mocks
        $productRepo = $this->createMock(EloquentProductRepository::class);

        // Caso de uso
        $useCase = new UpdateDeactivateProductUseCase($productRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
