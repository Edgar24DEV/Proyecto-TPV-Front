<?php

namespace Tests\Unit\Application\Product\UseCases;

use App\Application\Product\DTO\ListProductsRestaurantCommand;
use App\Application\Product\UseCases\ListAllProductsRestaurantUseCase;
use App\Domain\Product\Entities\Product;
use App\Domain\Product\Services\ProductService;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentProductRepository;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class ListAllProductsRestaurantUseCaseTest extends TestCase
{
    public function test_list_all_products_successfully()
    {
        // Arrange
        $command = new ListProductsRestaurantCommand(1);

        // Mocks
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
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
        $restaurantRepo->method('exist')->with($command->getIdRestaurant())->willReturn(true);
        $productRepo->method('findAll')->with($command->getIdRestaurant())->willReturn($products);
        $productService->method('showProductInfo')->with($products)->willReturn($processedProducts);

        // Caso de uso
        $useCase = new ListAllProductsRestaurantUseCase($restaurantRepo, $productRepo, $productService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertEquals("Producto A Info", $result[0]->nombre);
        $this->assertEquals("Producto B Info", $result[1]->nombre);
    }

    public function test_list_all_products_fails_when_invalid_restaurant_id()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID No existe");

        $command = new ListProductsRestaurantCommand(idRestaurante: 999); // Restaurante inexistente

        // Mocks
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $productRepo = $this->createMock(EloquentProductRepository::class);
        $productService = $this->createMock(ProductService::class);

        // Expectations
        $restaurantRepo->method('exist')->with($command->getIdRestaurant())->willReturn(false);

        // Caso de uso
        $useCase = new ListAllProductsRestaurantUseCase($restaurantRepo, $productRepo, $productService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
