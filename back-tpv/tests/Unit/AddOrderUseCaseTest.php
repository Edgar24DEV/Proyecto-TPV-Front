<?php

namespace Tests\Unit\Application\Order\UseCases;

use App\Application\Order\DTO\AddOrderCommand;
use App\Application\Order\UseCases\AddOrderUseCase;
use App\Domain\Order\Entities\Order;
use App\Domain\Order\Services\OrderService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use App\Infrastructure\Repositories\EloquentTableRepository;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Log;

class AddOrderUseCaseTest extends TestCase
{
    public function test_add_order_successfully()
    {
        // Arrange
        $command = new AddOrderCommand(
            idMesa: 1,
            comensales: 4
        );

        // Mocks
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $tableRepo = $this->createMock(EloquentTableRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $orderService = $this->createMock(OrderService::class);

        // Mock created order
        $newOrder = new Order(
            id: 1,
            estado: "activo",
            fechaInicio: now(),
            fechaFin: null,
            comensales: 4,
            idMesa: 1
        );

        // Expectations
        $tableRepo->method('exist')->with($command->getIdMesa())->willReturn(true);
        $orderRepo->method('create')->with($command)->willReturn($newOrder);
        $orderService->method('showOrderInfoSimple')->with($newOrder)->willReturn($newOrder);

        // Caso de uso
        $useCase = new AddOrderUseCase($orderRepo, $tableRepo, $companyRepo, $orderService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Order::class, $result);
        $this->assertEquals(1, $result->idMesa);
        $this->assertEquals(4, $result->comensales);
    }

    public function test_add_order_fails_when_invalid_table_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID mesa inválido");

        $command = new AddOrderCommand(
            idMesa: -1, // ID inválido
            comensales: 4
        );

        // Mocks
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $tableRepo = $this->createMock(EloquentTableRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $orderService = $this->createMock(OrderService::class);

        // Expectations
        $tableRepo->method('exist')->with($command->getIdMesa())->willReturn(false);

        // Caso de uso
        $useCase = new AddOrderUseCase($orderRepo, $tableRepo, $companyRepo, $orderService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_add_order_fails_when_invalid_comensales_count()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("El número de comensales debe ser mayor a 0");

        $command = new AddOrderCommand(
            idMesa: 1,
            comensales: 0 // Comensales inválidos
        );

        // Mocks
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $tableRepo = $this->createMock(EloquentTableRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $orderService = $this->createMock(OrderService::class);

        // Expectations
        $tableRepo->method('exist')->with($command->getIdMesa())->willReturn(true);
        // Caso de uso
        $useCase = new AddOrderUseCase($orderRepo, $tableRepo, $companyRepo, $orderService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_add_order_handles_exception()
    {
        // Arrange
        $command = new AddOrderCommand(
            idMesa: 1,
            comensales: 4
        );

        // Mocks
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $tableRepo = $this->createMock(EloquentTableRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $orderService = $this->createMock(OrderService::class);

        // Expectations
        $tableRepo->method('exist')->with($command->getIdMesa())->willReturn(true);
        $orderRepo->method('create')->willThrowException(new \Exception("Database error"));

        // Caso de uso
        $useCase = new AddOrderUseCase($orderRepo, $tableRepo, $companyRepo, $orderService);

        // Act & Assert
        $useCase($command);

    }
}
