<?php

namespace Tests\Unit\Application\Order\UseCases;

use App\Application\Order\DTO\UpdateOrderDinersCommand;
use App\Application\Order\UseCases\UpdateOrderDinersUseCase;
use App\Domain\Order\Entities\Order;
use App\Domain\Order\Services\OrderService;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Log;

class UpdateOrderDinersUseCaseTest extends TestCase
{
    public function test_update_order_diners_successfully()
    {
        // Arrange
        $command = new UpdateOrderDinersCommand(id: 1, comensales: 5);

        // Mocks
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $orderService = $this->createMock(OrderService::class);

        // Mock updated order
        $updatedOrder = new Order(
            id: 1,
            estado: "activo",
            fechaInicio: new \DateTime("2024-05-14"),
            fechaFin: null,
            comensales: 5,
            idMesa: 1
        );

        // Expectations
        $orderRepo->method('exist')->with($command->getId())->willReturn(true);
        $orderRepo->method('updateDiners')->with($command)->willReturn($updatedOrder);
        $orderService->method('showOrderInfoSimple')->with($updatedOrder)->willReturn($updatedOrder);

        // Caso de uso
        $useCase = new UpdateOrderDinersUseCase($orderRepo, $orderService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Order::class, $result);
        $this->assertEquals(5, $result->comensales);
    }

    public function test_update_order_diners_fails_when_invalid_order_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID de pedido inválido");

        $command = new UpdateOrderDinersCommand(id: -1, comensales: 4); // ID inválido

        // Mocks
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $orderService = $this->createMock(OrderService::class);

        // Expectations
        $orderRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new UpdateOrderDinersUseCase($orderRepo, $orderService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_update_order_diners_handles_exception()
    {
        // Arrange
        $command = new UpdateOrderDinersCommand(id: 1, comensales: 5);

        // Mocks
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $orderService = $this->createMock(OrderService::class);

        // Expectations
        $orderRepo->method('exist')->with($command->getId())->willReturn(true);
        $orderRepo->method('updateDiners')->willThrowException(new \Exception("Database error"));

        // Caso de uso
        $useCase = new UpdateOrderDinersUseCase($orderRepo, $orderService);

        // Act & Assert

        $useCase($command);

    }
}
