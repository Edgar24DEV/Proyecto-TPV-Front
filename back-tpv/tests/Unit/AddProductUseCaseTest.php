<?php

namespace Tests\Unit\Application\Product\UseCases;

use App\Application\Product\DTO\AddProductCommand;
use App\Application\Product\UseCases\AddProductUseCase;
use App\Domain\Product\Entities\Product;
use App\Domain\Product\Services\ProductService;
use App\Infrastructure\Repositories\EloquentCategoryRepository;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentProductRepository;
use PHPUnit\Framework\TestCase;

class AddProductUseCaseTest extends TestCase
{
    public function test_add_product_successfully()
    {
        // Arrange
        $command = new AddProductCommand(
            nombre: "Nuevo Producto",
            precio: 20.99,
            imagen: "imagen_producto.jpg",
            iva: 10.0,
            idCategoria: 1,
            idEmpresa: 1
        );

        // Mocks
        $productRepo = $this->createMock(EloquentProductRepository::class);
        $categoryRepo = $this->createMock(EloquentCategoryRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);

        // Mock product data
        $newProduct = new Product(
            id: 1,
            nombre: "Nuevo Producto",
            precio: 20.99,
            imagen: "imagen_producto.jpg",
            activo: true,
            iva: 10.0,
            idCategoria: 1,
            idEmpresa: 1
        );

        // Expectations
        $categoryRepo->method('exist')->with($command->getIdCategoria())->willReturn(true);
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(true);
        $productRepo->method('notEqual')->with($command->getNombre(), $command->getIdEmpresa())->willReturn(false);
        $productRepo->method('save')->with($this->callback(function ($product) use ($command) {
            return $product->nombre === $command->getNombre() &&
                $product->precio === $command->getPrecio() &&
                $product->imagen === $command->getImagen() &&
                $product->iva === $command->getIva() &&
                $product->idCategoria === $command->getIdCategoria() &&
                $product->idEmpresa === $command->getIdEmpresa();
        }))->willReturn($newProduct);

        // Caso de uso
        $useCase = new AddProductUseCase($productRepo, $categoryRepo, $companyRepo);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals("Nuevo Producto", $result->nombre);
        $this->assertEquals(20.99, $result->precio);
    }

    public function test_add_product_fails_when_invalid_category()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("La categoría especificada no existe");

        $command = new AddProductCommand(
            nombre: "Nuevo Producto",
            precio: 20.99,
            imagen: "imagen_producto.jpg",
            iva: 10.0,
            idCategoria: 999, // Categoría inexistente
            idEmpresa: 1
        );

        // Mocks
        $productRepo = $this->createMock(EloquentProductRepository::class);
        $categoryRepo = $this->createMock(EloquentCategoryRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);

        $categoryRepo->method('exist')->with($command->getIdCategoria())->willReturn(false);

        // Caso de uso
        $useCase = new AddProductUseCase($productRepo, $categoryRepo, $companyRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_add_product_fails_when_invalid_company()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("La empresa especificada no existe");

        $command = new AddProductCommand(
            nombre: "Nuevo Producto",
            precio: 20.99,
            imagen: "imagen_producto.jpg",
            iva: 10.0,
            idCategoria: 1,
            idEmpresa: 999 // Empresa inexistente
        );

        // Mocks
        $productRepo = $this->createMock(EloquentProductRepository::class);
        $categoryRepo = $this->createMock(EloquentCategoryRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);

        // Configuración correcta del mock para categoría
        $categoryRepo->method('exist')->with($command->getIdCategoria())->willReturn(true);

        // Mock para empresa inexistente
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(false);

        // Caso de uso
        $useCase = new AddProductUseCase($productRepo, $categoryRepo, $companyRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }


    public function test_add_product_fails_when_product_already_exists()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Ya existe un producto con ese nombre");

        $command = new AddProductCommand(
            nombre: "Producto Existente",
            precio: 20.99,
            imagen: "imagen_producto.jpg",
            iva: 10.0,
            idCategoria: 1,
            idEmpresa: 1
        );

        // Mocks
        $productRepo = $this->createMock(EloquentProductRepository::class);
        $categoryRepo = $this->createMock(EloquentCategoryRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);

        $categoryRepo->method('exist')->with($command->getIdCategoria())->willReturn(true);
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(true);
        $productRepo->method('notEqual')->with($command->getNombre(), $command->getIdEmpresa())->willReturn(true);

        // Caso de uso
        $useCase = new AddProductUseCase($productRepo, $categoryRepo, $companyRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
