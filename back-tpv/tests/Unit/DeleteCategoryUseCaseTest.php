<?php

namespace Tests\Unit\Application\Category\UseCases;

use App\Application\Category\DTO\DeleteCategoryCommand;
use App\Application\Category\UseCases\DeleteCategoryUseCase;
use App\Infrastructure\Repositories\EloquentCategoryRepository;
use PHPUnit\Framework\TestCase;

class DeleteCategoryUseCaseTest extends TestCase
{
    public function test_delete_category_successfully()
    {
        // Arrange
        $command = new DeleteCategoryCommand(id: 1);

        // Mocks
        $categoryRepo = $this->createMock(EloquentCategoryRepository::class);

        // Expectations
        $categoryRepo->method('exist')->with($command->getId())->willReturn(true);
        $categoryRepo->method('delete')->with($command->getId())->willReturn(true);

        // Caso de uso
        $useCase = new DeleteCategoryUseCase($categoryRepo);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertTrue($result);
    }

    public function test_delete_category_fails_when_invalid_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID categoría inválido");

        $command = new DeleteCategoryCommand(id: -1);

        // Mocks
        $categoryRepo = $this->createMock(EloquentCategoryRepository::class);

        // Expectations
        $categoryRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new DeleteCategoryUseCase($categoryRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_delete_category_handles_exception()
    {
        // Arrange
        $command = new DeleteCategoryCommand(id: 1);

        // Mocks
        $categoryRepo = $this->createMock(EloquentCategoryRepository::class);

        // Expectations
        $categoryRepo->method('exist')->with($command->getId())->willReturn(true);
        $categoryRepo->method('delete')->with($command->getId())->willThrowException(new \Exception("Error al eliminar"));

        // Caso de uso
        $useCase = new DeleteCategoryUseCase($categoryRepo);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertFalse($result); // Se espera que retorne `false` si hay una excepción
    }
}
