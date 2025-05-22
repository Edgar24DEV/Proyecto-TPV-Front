<?php

namespace Tests\Unit\Application\Order\UseCases;

use App\Application\Order\DTO\GetOrderCommand;
use App\Application\Order\UseCases\GetOrderUseCase;
use App\Domain\Order\Entities\Order;
use App\Domain\Order\Services\OrderService;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use App\Infrastructure\Repositories\EloquentTableRepository;
use PHPUnit\Framework\TestCase;

class GetOrderUseCaseTest extends TestCase
{
    public function test_get_order_successfully()
    {
        // Arrange
        $command = new GetOrderCommand(idMesa: 1);

        // Mocks
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $tableRepo = $this->createMock(EloquentTableRepository::class);
        $orderService = $this->createMock(OrderService::class);

        // Mock order
        $order = new Order(
            id: 1,
            estado: "activo",
            fechaInicio: new \DateTime("2024-05-14"),
            fechaFin: null,
            comensales: 4,
            idMesa: 1
        );

        // Expectations
        $tableRepo->method('exist')->with($command->getIdMesa())->willReturn(true);
        $orderRepo->method('getOrder')->with($command)->willReturn($order);
        $orderService->method('showOrderInfoSimple')->with($order)->willReturn($order);

        // Caso de uso
        $useCase = new GetOrderUseCase($orderRepo, $tableRepo, $orderService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Order::class, $result);
        $this->assertEquals(1, $result->idMesa);
        $this->assertEquals(4, $result->comensales);
        $this->assertEquals("activo", $result->estado);
    }

    public function test_get_order_fails_when_invalid_table_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID mesa inválido");

        $command = new GetOrderCommand(idMesa: -1); // ID inválido

        // Mocks
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $tableRepo = $this->createMock(EloquentTableRepository::class);
        $orderService = $this->createMock(OrderService::class);

        // Expectations
        $tableRepo->method('exist')->with($command->getIdMesa())->willReturn(false);

        // Caso de uso
        $useCase = new GetOrderUseCase($orderRepo, $tableRepo, $orderService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_get_order_handles_exception()
    {
        // Arrange
        $command = new GetOrderCommand(idMesa: 1);

        // Mocks
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $tableRepo = $this->createMock(EloquentTableRepository::class);
        $orderService = $this->createMock(OrderService::class);

        // Expectations
        $tableRepo->method('exist')->with($command->getIdMesa())->willReturn(true);
        $orderRepo->method('getOrder')->with($command)->willThrowException(new \Exception("Database error"));

        // Caso de uso
        $useCase = new GetOrderUseCase($orderRepo, $tableRepo, $orderService);

        // Act & Assert
        try {
            $useCase($command);
        } catch (\Exception $e) {
            $this->assertEquals("Database error", $e->getMessage());
        }
    }
}
