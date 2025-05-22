<?php

namespace Tests\Unit\Application\OrderLine\UseCases;

use App\Application\OrderLine\DTO\DeleteOrderLineCommand;
use App\Application\OrderLine\UseCases\DeleteOrderLineUseCase;
use App\Infrastructure\Repositories\EloquentOrderLineRepository;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Log;

class DeleteOrderLineUseCaseTest extends TestCase
{
    public function test_delete_order_line_successfully()
    {
        // Arrange
        $command = new DeleteOrderLineCommand(id: 1);

        // Mocks
        $orderLineRepo = $this->createMock(EloquentOrderLineRepository::class);

        // Expectations
        $orderLineRepo->method('exist')->with($command->getId())->willReturn(true);
        $orderLineRepo->method('delete')->with($command->getId())->willReturn(true);

        // Caso de uso
        $useCase = new DeleteOrderLineUseCase($orderLineRepo);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertTrue($result);
    }

    public function test_delete_order_line_fails_when_line_not_found()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID linea pedido inválido");

        $command = new DeleteOrderLineCommand(id: 999); // Línea inexistente

        // Mocks
        $orderLineRepo = $this->createMock(EloquentOrderLineRepository::class);

        // Expectations
        $orderLineRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new DeleteOrderLineUseCase($orderLineRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_delete_order_line_handles_exception()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Database error");

        $command = new DeleteOrderLineCommand(id: 1);

        // Mocks
        $orderLineRepo = $this->createMock(EloquentOrderLineRepository::class);

        // Expectations
        $orderLineRepo->method('exist')->with($command->getId())->willReturn(true);
        $orderLineRepo->method('delete')->with($command->getId())->willThrowException(new \Exception("Database error"));

        // Caso de uso
        $useCase = new DeleteOrderLineUseCase($orderLineRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
