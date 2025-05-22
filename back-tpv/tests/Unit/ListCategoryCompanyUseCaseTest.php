<?php

namespace Tests\Unit\Application\Category\UseCases;

use App\Application\Category\DTO\ListCategoryCompanyCommand;
use App\Application\Category\UseCases\ListCategoryCompanyUseCase;
use App\Domain\Product\Services\CategoryService;
use App\Infrastructure\Repositories\EloquentCategoryRepository;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class ListCategoryCompanyUseCaseTest extends TestCase
{
    public function test_list_categories_successfully()
    {
        // Arrange
        $command = new ListCategoryCompanyCommand(id: 1);

        // Mocks
        $categoryRepo = $this->createMock(EloquentCategoryRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
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
        $companyRepo->method('exist')->with($command->getId())->willReturn(true);
        $categoryRepo->method('findByCompany')->with($command->getId())->willReturn($categories);
        $categoryService->method('showCategoryInfo')->with($categories)->willReturn($processedCategories);

        // Caso de uso
        $useCase = new ListCategoryCompanyUseCase($categoryRepo, $companyRepo, $categoryService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertEquals("Bebidas - procesado", $result[0]['categoria']);
        $this->assertEquals("Comida - procesado", $result[1]['categoria']);
    }

    public function test_list_categories_fails_when_invalid_company_id()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID No existe");

        $command = new ListCategoryCompanyCommand(id: 999); // ID inválido

        // Mocks
        $categoryRepo = $this->createMock(EloquentCategoryRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $categoryService = $this->createMock(CategoryService::class);

        // Expectations
        $companyRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new ListCategoryCompanyUseCase($categoryRepo, $companyRepo, $categoryService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
