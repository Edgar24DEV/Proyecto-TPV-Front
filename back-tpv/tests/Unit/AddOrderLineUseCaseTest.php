<?php

namespace Tests\Unit\Application\OrderLine\UseCases;

use App\Application\OrderLine\DTO\AddOrderLineCommand;
use App\Application\OrderLine\DTO\UpdateOrderLineCommand;
use App\Application\OrderLine\UseCases\AddOrderLineUseCase;
use App\Domain\Order\Entities\OrderLine;
use App\Domain\Order\Services\OrderLineService;
use App\Infrastructure\Repositories\EloquentOrderLineRepository;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use PHPUnit\Framework\TestCase;

class AddOrderLineUseCaseTest extends TestCase
{
    public function test_add_order_line_successfully_creates_new_line()
    {
        // Arrange
        $command = new AddOrderLineCommand(
            idOrder: 1,
            idProduct: 5,
            quantity: 3,
            description: "Sin aderezo",
            state: "pendiente"
        );

        // Mocks
        $orderLineRepo = $this->createMock(EloquentOrderLineRepository::class);
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $orderLineService = $this->createMock(OrderLineService::class);

        // Mock new order line creation
        $newOrderLine = new OrderLine(
            id: 1,
            idPedido: 1,
            idProducto: 5,
            cantidad: 3,
            precio: 20.00,
            nombre: "Producto X",
            observaciones: "Sin aderezo",
            estado: "pendiente"
        );

        // Expectations
        $orderRepo->method('exist')->with($command->getIdOrder())->willReturn(true);
        $orderLineRepo->method('findByOrderAndProduct')->with($command->getIdOrder(), $command->getIdProduct())->willReturn(null);
        $orderLineRepo->method('create')->with($command)->willReturn($newOrderLine);
        $orderLineService->method('showOrderLineInfoSimple')->with($newOrderLine)->willReturn($newOrderLine);

        // Caso de uso
        $useCase = new AddOrderLineUseCase($orderLineRepo, $orderLineService, $orderRepo);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(OrderLine::class, $result);
        $this->assertEquals(3, $result->cantidad);
        $this->assertEquals("Producto X", $result->nombre);
        $this->assertEquals("pendiente", $result->estado);
        $this->assertEquals("Sin aderezo", $result->observaciones);
    }

    public function test_add_order_line_updates_existing_line()
    {
        // Arrange
        $command = new AddOrderLineCommand(
            idOrder: 1,
            idProduct: 5,
            quantity: 2,
            description: "Extra salsa",
            state: "pendiente"
        );

        // Mocks
        $orderLineRepo = $this->createMock(EloquentOrderLineRepository::class);
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $orderLineService = $this->createMock(OrderLineService::class);

        // Mock existing order line
        $existingOrderLine = new OrderLine(
            id: 1,
            idPedido: 1,
            idProducto: 5,
            cantidad: 3,
            precio: 20.00,
            nombre: "Producto X",
            observaciones: "",
            estado: "pendiente"
        );

        $updatedOrderLine = new OrderLine(
            id: 1,
            idPedido: 1,
            idProducto: 5,
            cantidad: 5, // Actualización de cantidad (3 + 2)
            precio: 20.00,
            nombre: "Producto X",
            observaciones: "Extra salsa",
            estado: "pendiente"
        );

        // Expectations
        $orderRepo->method('exist')->with($command->getIdOrder())->willReturn(true);
        $orderLineRepo->method('findByOrderAndProduct')->with($command->getIdOrder(), $command->getIdProduct())->willReturn($existingOrderLine);
        $orderLineRepo->method('update')->with($this->callback(function ($updateCommand) {
            return $updateCommand instanceof UpdateOrderLineCommand &&
                $updateCommand->getQuantity() === 5;
        }))->willReturn($updatedOrderLine);
        $orderLineService->method('showOrderLineInfoSimple')->with($updatedOrderLine)->willReturn($updatedOrderLine);

        // Caso de uso
        $useCase = new AddOrderLineUseCase($orderLineRepo, $orderLineService, $orderRepo);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(OrderLine::class, $result);
        $this->assertEquals(5, $result->cantidad);
        $this->assertEquals("Extra salsa", $result->observaciones);
    }

    public function test_add_order_line_fails_when_order_not_found()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID No existe");

        $command = new AddOrderLineCommand(
            idOrder: 999, // Pedido inexistente
            idProduct: 5,
            quantity: 1,
            description: "Sin azúcar",
            state: "pendiente"
        );

        // Mocks
        $orderLineRepo = $this->createMock(EloquentOrderLineRepository::class);
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $orderLineService = $this->createMock(OrderLineService::class);

        // Expectations
        $orderRepo->method('exist')->with($command->getIdOrder())->willReturn(false);

        // Caso de uso
        $useCase = new AddOrderLineUseCase($orderLineRepo, $orderLineService, $orderRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
