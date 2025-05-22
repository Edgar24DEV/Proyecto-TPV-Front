<?php

namespace Tests\Unit\Application\Category\UseCases;

use App\Application\Category\DTO\GetCategoryCompanyCommand;
use App\Application\Category\UseCases\GetCategoryCompanyUseCase;
use App\Domain\Product\Entities\Category;
use App\Domain\Product\Services\CategoryService;
use App\Infrastructure\Repositories\EloquentCategoryRepository;
use PHPUnit\Framework\TestCase;

class GetCategoryCompanyUseCaseTest extends TestCase
{
    public function test_get_category_successfully()
    {
        // Arrange
        $command = new GetCategoryCompanyCommand(id: 1);

        // Mocks
        $categoryRepo = $this->createMock(EloquentCategoryRepository::class);
        $categoryService = $this->createMock(CategoryService::class);

        // Mock existing category
        $category = new Category(
            id: 1,
            categoria: "Bebidas",
            activo: true,
            idEmpresa: 1
        );

        // Expectations
        $categoryRepo->method('exist')->with($command->getId())->willReturn(true);
        $categoryRepo->method('getCategory')->with($command->getId())->willReturn($category);
        $categoryService->method('showCategoryInfoSimple')->with($category)->willReturn($category);

        // Caso de uso
        $useCase = new GetCategoryCompanyUseCase($categoryRepo, $categoryService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Category::class, $result);
        $this->assertEquals("Bebidas", $result->categoria);
        $this->assertTrue($result->activo);
        $this->assertEquals(1, $result->idEmpresa);
    }

    public function test_get_category_fails_when_invalid_id()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID inválido");

        $command = new GetCategoryCompanyCommand(id: -1); // ID inválido

        // Mocks
        $categoryRepo = $this->createMock(EloquentCategoryRepository::class);
        $categoryService = $this->createMock(CategoryService::class);

        // Expectations
        $categoryRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new GetCategoryCompanyUseCase($categoryRepo, $categoryService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_get_category_fails_when_category_not_found()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID No existe");

        $command = new GetCategoryCompanyCommand(id: 999); // ID inexistente

        // Mocks
        $categoryRepo = $this->createMock(EloquentCategoryRepository::class);
        $categoryService = $this->createMock(CategoryService::class);

        // Expectations
        $categoryRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new GetCategoryCompanyUseCase($categoryRepo, $categoryService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
