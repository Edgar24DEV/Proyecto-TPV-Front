<?php

namespace Tests\Unit\Application\Order\UseCases;

use App\Application\Order\DTO\UpdateOrderStatusCommand;
use App\Application\Order\UseCases\UpdateOrderStatusUseCase;
use App\Domain\Order\Entities\Order;
use App\Domain\Order\Services\OrderService;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Log;

class UpdateOrderStatusUseCaseTest extends TestCase
{
    public function test_update_order_status_successfully()
    {
        // Arrange
        $command = new UpdateOrderStatusCommand(id: 1, estado: "Procesando");

        // Mocks
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $orderService = $this->createMock(OrderService::class);

        // Mock updated order
        $updatedOrder = new Order(
            id: 1,
            estado: "Procesando",
            fechaInicio: new \DateTime("2024-05-14"),
            fechaFin: null,
            comensales: 4,
            idMesa: 1
        );

        // Expectations
        $orderRepo->method('exist')->with($command->getId())->willReturn(true);
        $orderRepo->method('updateStatus')->with($command)->willReturn($updatedOrder);
        $orderService->method('showOrderInfoSimple')->with($updatedOrder)->willReturn($updatedOrder);

        // Caso de uso
        $useCase = new UpdateOrderStatusUseCase($orderRepo, $orderService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Order::class, $result);
        $this->assertEquals("Procesando", $result->estado);
    }

    public function test_update_order_status_fails_when_invalid_order_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID de pedido inválido");

        $command = new UpdateOrderStatusCommand(id: -1, estado: "Procesando"); // ID inválido

        // Mocks
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $orderService = $this->createMock(OrderService::class);

        // Expectations
        $orderRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new UpdateOrderStatusUseCase($orderRepo, $orderService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_update_order_status_fails_when_order_is_closed_or_cancelled()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("El pedido no puede ser modificado porque ya está cancelado o cerrado");

        $command = new UpdateOrderStatusCommand(id: 1, estado: "Procesando");

        // Mocks
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $orderService = $this->createMock(OrderService::class);

        // Mock existing closed order
        $existingOrder = new Order(
            id: 1,
            estado: "Cancelado",
            fechaInicio: new \DateTime("2024-05-14"),
            fechaFin: new \DateTime("2024-05-15"),
            comensales: 4,
            idMesa: 1
        );

        // Expectations
        $orderRepo->method('exist')->with($command->getId())->willReturn(true);
        $orderRepo->method('findByID')->with($command)->willReturn($existingOrder);

        // Caso de uso
        $useCase = new UpdateOrderStatusUseCase($orderRepo, $orderService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_update_order_status_handles_exception()
    {
        // Arrange
        $command = new UpdateOrderStatusCommand(id: 1, estado: "Procesando");

        // Mocks
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $orderService = $this->createMock(OrderService::class);

        // Expectations
        $orderRepo->method('exist')->with($command->getId())->willReturn(true);
        $orderRepo->method('updateStatus')->willThrowException(new \Exception("Database error"));

        // Caso de uso
        $useCase = new UpdateOrderStatusUseCase($orderRepo, $orderService);

        // Act & Assert

        $useCase($command);

    }
}
