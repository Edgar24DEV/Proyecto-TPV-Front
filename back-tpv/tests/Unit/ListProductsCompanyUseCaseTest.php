<?php

namespace Tests\Unit\Application\Product\UseCases;

use App\Application\Product\DTO\ListProductsCompanyCommand;
use App\Application\Product\UseCases\ListProductsCompanyUseCase;
use App\Domain\Product\Entities\Product;
use App\Domain\Product\Services\ProductService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentProductRepository;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class ListProductsCompanyUseCaseTest extends TestCase
{
    public function test_list_products_company_successfully()
    {
        // Arrange
        $command = new ListProductsCompanyCommand(idEmpresa: 1);

        // Mocks
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $productRepo = $this->createMock(EloquentProductRepository::class);
        $productService = $this->createMock(ProductService::class);

        // Mock product data
        $products = [
            new Product(id: 1, nombre: "Producto A", precio: 10.0, imagen: "img_a.jpg", activo: true, iva: 21.0, idCategoria: 1, idEmpresa: 1),
            new Product(id: 2, nombre: "Producto B", precio: 15.0, imagen: "img_b.jpg", activo: true, iva: 21.0, idCategoria: 1, idEmpresa: 1)
        ];

        $processedProducts = new Collection([
            new Product(id: 1, nombre: "Producto A Info", precio: 10.0, imagen: "img_a.jpg", activo: true, iva: 21.0, idCategoria: 1, idEmpresa: 1),
            new Product(id: 2, nombre: "Producto B Info", precio: 15.0, imagen: "img_b.jpg", activo: true, iva: 21.0, idCategoria: 1, idEmpresa: 1)
        ]);

        // Expectations
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(true);
        $productRepo->method('findByCompany')->with($command->getIdEmpresa())->willReturn($products);
        $productService->method('showProductInfo')->with($products)->willReturn($processedProducts);

        // Caso de uso
        $useCase = new ListProductsCompanyUseCase($companyRepo, $productRepo, $productService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertEquals("Producto A Info", $result[0]->nombre);
        $this->assertEquals("Producto B Info", $result[1]->nombre);
    }

    public function test_list_products_company_fails_when_invalid_company_id()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID No existe");

        $command = new ListProductsCompanyCommand(idEmpresa: 999); // Empresa inexistente

        // Mocks
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $productRepo = $this->createMock(EloquentProductRepository::class);
        $productService = $this->createMock(ProductService::class);

        // Expectations
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(false);

        // Caso de uso
        $useCase = new ListProductsCompanyUseCase($companyRepo, $productRepo, $productService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
