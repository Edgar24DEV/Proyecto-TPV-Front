<?php

namespace Tests\Unit\Application\Category\UseCases;

use App\Application\Category\DTO\UpdateActiveCategoryCommand;
use App\Application\Category\UseCases\UpdateActiveCategoryUseCase;
use App\Domain\Product\Entities\Category;
use App\Domain\Product\Services\CategoryService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentCategoryRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Log;

class UpdateActiveCategoryUseCaseTest extends TestCase
{
    public function test_update_active_category_successfully()
    {
        // Arrange
        $command = new UpdateActiveCategoryCommand(id: 1, categoria: "Bebidas", activo: true, idEmpresa: 1);

        // Mocks
        $categoryRepo = $this->createMock(EloquentCategoryRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $categoryService = $this->createMock(CategoryService::class);

        // Mock updated category
        $updatedCategory = new Category(
            id: 1,
            categoria: "Bebidas",
            activo: true,
            idEmpresa: 1
        );

        // Expectations
        $categoryRepo->method('exist')->with($command->getId())->willReturn(true);
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(true);
        $categoryRepo->method('updateActive')->with($command)->willReturn($updatedCategory);
        $categoryService->method('showCategoryInfoSimple')->with($updatedCategory)->willReturn($updatedCategory);

        // Caso de uso
        $useCase = new UpdateActiveCategoryUseCase($categoryRepo, $restaurantRepo, $roleRepo, $companyRepo, $categoryService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Category::class, $result);
        $this->assertEquals("Bebidas", $result->categoria);
        $this->assertTrue($result->activo);
        $this->assertEquals(1, $result->idEmpresa);
    }

    public function test_update_active_category_fails_when_category_not_found()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID categoria inválido");

        $command = new UpdateActiveCategoryCommand(id: 999, categoria: "Bebidas", activo: true, idEmpresa: 1); // ID inexistente

        // Mocks
        $categoryRepo = $this->createMock(EloquentCategoryRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $categoryService = $this->createMock(CategoryService::class);

        // Expectations
        $categoryRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new UpdateActiveCategoryUseCase($categoryRepo, $restaurantRepo, $roleRepo, $companyRepo, $categoryService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_update_active_category_fails_when_invalid_company_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID empresa inválido");

        $command = new UpdateActiveCategoryCommand(id: 1, categoria: "Bebidas", activo: true, idEmpresa: 999); // ID empresa inexistente

        // Mocks
        $categoryRepo = $this->createMock(EloquentCategoryRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $categoryService = $this->createMock(CategoryService::class);

        // Expectations
        $categoryRepo->method('exist')->with($command->getId())->willReturn(true);
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(false);

        // Caso de uso
        $useCase = new UpdateActiveCategoryUseCase($categoryRepo, $restaurantRepo, $roleRepo, $companyRepo, $categoryService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
