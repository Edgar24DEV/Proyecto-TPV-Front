<?php

namespace Tests\Unit\Application\Order\UseCases;

use App\Application\Order\DTO\DeleteOrderCommand;
use App\Application\Order\UseCases\DeleteOrderUseCase;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Log;

class DeleteOrderUseCaseTest extends TestCase
{
    public function test_delete_order_successfully()
    {
        // Arrange
        $command = new DeleteOrderCommand(id: 1);

        // Mocks
        $orderRepo = $this->createMock(EloquentOrderRepository::class);

        // Expectations
        $orderRepo->method('exist')->with($command->getId())->willReturn(true);
        $orderRepo->method('delete')->with($command->getId())->willReturn(true);

        // Caso de uso
        $useCase = new DeleteOrderUseCase($orderRepo);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertTrue($result);
    }

    public function test_delete_order_fails_when_order_not_found()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID de pedido inválido");

        $command = new DeleteOrderCommand(id: 999); // Pedido inexistente

        // Mocks
        $orderRepo = $this->createMock(EloquentOrderRepository::class);

        // Expectations
        $orderRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new DeleteOrderUseCase($orderRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_delete_order_handles_exception()
    {
        $command = new DeleteOrderCommand(id: 1);

        // Mocks
        $orderRepo = $this->createMock(EloquentOrderRepository::class);

        // Expectations
        $orderRepo->method('exist')->with($command->getId())->willReturn(true);


        // Caso de uso
        $useCase = new DeleteOrderUseCase($orderRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
