<?php

namespace Tests\Unit\Application\Category\UseCases;

use App\Application\Category\DTO\AddCategoryCommand;
use App\Application\Category\UseCases\AddCategoryUseCase;
use App\Domain\Product\Entities\Category;
use App\Domain\Product\Services\CategoryService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentCategoryRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Log;

class AddCategoryUseCaseTest extends TestCase
{
    public function test_add_category_successfully()
    {
        // Arrange
        $command = new AddCategoryCommand(idEmpresa: 1, categoria: "Bebidas");

        // Mocks
        $categoryRepo = $this->createMock(EloquentCategoryRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $categoryService = $this->createMock(CategoryService::class);

        // Mock created category
        $newCategory = new Category(
            id: 1,
            categoria: "Bebidas",
            activo: true,
            idEmpresa: 1
        );

        // Expectations
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(true);
        $categoryRepo->method('existCategory')->with($command->getCategoria(), $command->getIdEmpresa())->willReturn(false);
        $categoryRepo->method('create')->with($command)->willReturn($newCategory);
        $categoryService->method('showCategoryInfoSimple')->with($newCategory)->willReturn($newCategory);

        // Caso de uso
        $useCase = new AddCategoryUseCase($categoryRepo, $restaurantRepo, $roleRepo, $companyRepo, $categoryService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Category::class, $result);
        $this->assertEquals("Bebidas", $result->categoria);
        $this->assertTrue($result->activo);
        $this->assertEquals(1, $result->idEmpresa);
    }

    public function test_add_category_fails_when_invalid_company_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID empresa inválido");

        $command = new AddCategoryCommand(idEmpresa: -1, categoria: "Bebidas");

        // Mocks
        $categoryRepo = $this->createMock(EloquentCategoryRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $categoryService = $this->createMock(CategoryService::class);

        // Expectations
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(false);

        // Caso de uso
        $useCase = new AddCategoryUseCase($categoryRepo, $restaurantRepo, $roleRepo, $companyRepo, $categoryService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_add_category_fails_when_category_already_exists()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Ya existe la categoria");

        $command = new AddCategoryCommand(idEmpresa: 1, categoria: "Bebidas");

        // Mocks
        $categoryRepo = $this->createMock(EloquentCategoryRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $categoryService = $this->createMock(CategoryService::class);

        // Expectations
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(true);
        $categoryRepo->method('existCategory')->with($command->getCategoria(), $command->getIdEmpresa())->willReturn(true);

        // Caso de uso
        $useCase = new AddCategoryUseCase($categoryRepo, $restaurantRepo, $roleRepo, $companyRepo, $categoryService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
