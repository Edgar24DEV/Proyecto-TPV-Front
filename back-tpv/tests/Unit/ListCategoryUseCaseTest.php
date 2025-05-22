<?php

namespace Tests\Unit\Application\Category\UseCases;

use App\Application\Category\DTO\ListCategoryCommand;
use App\Application\Category\UseCases\ListCategoryUseCase;
use App\Domain\Product\Services\CategoryService;
use App\Infrastructure\Repositories\EloquentCategoryRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class ListCategoryUseCaseTest extends TestCase
{
    public function test_list_categories_successfully()
    {
        // Arrange
        $command = new ListCategoryCommand(idRestaurante: 1);

        // Mocks
        $categoryRepo = $this->createMock(EloquentCategoryRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $categoryService = $this->createMock(CategoryService::class);

        // Mock category list
        $categories = [
            ['id' => 1, 'categoria' => 'Bebidas'],
            ['id' => 2, 'categoria' => 'Comida'],
        ];

        $processedCategories = new Collection([
            ['id' => 1, 'categoria' => 'Bebidas - procesado'],
            ['id' => 2, 'categoria' => 'Comida - procesado'],
        ]);

        // Expectations
        $restaurantRepo->method('exist')->with($command->getIdRestaurant())->willReturn(true);
        $categoryRepo->method('find')->with($command->getIdRestaurant())->willReturn($categories);
        $categoryService->method('showCategoryInfo')->with($categories)->willReturn($processedCategories);

        // Caso de uso
        $useCase = new ListCategoryUseCase($categoryRepo, $restaurantRepo, $categoryService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertEquals("Bebidas - procesado", $result[0]['categoria']);
        $this->assertEquals("Comida - procesado", $result[1]['categoria']);
    }

    public function test_list_categories_fails_when_invalid_restaurant_id()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID No existe");

        $command = new ListCategoryCommand(idRestaurante: 999); // ID inválido

        // Mocks
        $categoryRepo = $this->createMock(EloquentCategoryRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $categoryService = $this->createMock(CategoryService::class);

        // Expectations
        $restaurantRepo->method('exist')->with($command->getIdRestaurant())->willReturn(false);

        // Caso de uso
        $useCase = new ListCategoryUseCase($categoryRepo, $restaurantRepo, $categoryService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
