<?php

namespace Tests\Unit\Application\Product\UseCases;

use App\Application\Product\DTO\DeleteProductCommand;
use App\Application\Product\UseCases\DeleteProductUseCase;
use App\Infrastructure\Repositories\EloquentProductRepository;
use PHPUnit\Framework\TestCase;

class DeleteProductUseCaseTest extends TestCase
{
    public function test_delete_product_successfully()
    {
        // Arrange
        $command = new DeleteProductCommand(id: 1);

        // Mocks
        $productRepo = $this->createMock(EloquentProductRepository::class);

        // Expectations
        $productRepo->method('exist')->with($command->getId())->willReturn(true);
        $productRepo->method('delete')->with($command->getId())->willReturn(true);

        // Caso de uso
        $useCase = new DeleteProductUseCase($productRepo);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertTrue($result);
    }

    public function test_delete_product_fails_when_product_not_found()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID de pedido inválido");

        $command = new DeleteProductCommand(id: 999); // ID inexistente

        // Mocks
        $productRepo = $this->createMock(EloquentProductRepository::class);

        // Expectations
        $productRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new DeleteProductUseCase($productRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_delete_product_fails_when_invalid_product_id()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID de pedido inválido");

        $command = new DeleteProductCommand(id: 0); // ID inválido

        // Mocks
        $productRepo = $this->createMock(EloquentProductRepository::class);

        // Expectations
        $productRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new DeleteProductUseCase($productRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
