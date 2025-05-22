<?php

namespace Tests\Unit\Application\OrderLine\UseCases;

use App\Application\OrderLine\DTO\UpdateOrderLineCommand;
use App\Application\OrderLine\UseCases\UpdateOrderLineUseCase;
use App\Domain\Order\Entities\OrderLine;
use App\Domain\Order\Services\OrderLineService;
use App\Infrastructure\Repositories\EloquentOrderLineRepository;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Log;

class UpdateOrderLineUseCaseTest extends TestCase
{
    public function test_update_order_line_successfully()
    {
        // Arrange
        $command = new UpdateOrderLineCommand(
            id: 1,
            quantity: 3,
            name: "Producto X",
            price: 20.00
        );

        // Mocks
        $orderLineRepo = $this->createMock(EloquentOrderLineRepository::class);
        $orderLineService = $this->createMock(OrderLineService::class);

        // Mock updated order line
        $updatedOrderLine = new OrderLine(
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
        $orderLineRepo->method('exist')->with($command->getId())->willReturn(true);
        $orderLineRepo->method('update')->with($command)->willReturn($updatedOrderLine);
        $orderLineService->method('showOrderLineInfoSimple')->with($updatedOrderLine)->willReturn($updatedOrderLine);

        // Caso de uso
        $useCase = new UpdateOrderLineUseCase($orderLineRepo, $orderLineService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(OrderLine::class, $result);
        $this->assertEquals("Producto X", $result->nombre);
        $this->assertEquals(3, $result->cantidad);
        $this->assertEquals(20.00, $result->precio);
        $this->assertEquals("pendiente", $result->estado);
    }

    public function test_update_order_line_fails_when_invalid_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID linea pedido inválido");

        $command = new UpdateOrderLineCommand(
            id: -1, // ID inválido
            quantity: 2,
            name: "Producto Y",
            price: 15.00
        );

        // Mocks
        $orderLineRepo = $this->createMock(EloquentOrderLineRepository::class);
        $orderLineService = $this->createMock(OrderLineService::class);

        // Expectations
        $orderLineRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new UpdateOrderLineUseCase($orderLineRepo, $orderLineService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_update_order_line_fails_when_invalid_name()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Nombre de producto no valido");

        $command = new UpdateOrderLineCommand(
            id: 1, // ID válido para que la validación del nombre ocurra
            quantity: 2,
            name: "", // Nombre inválido
            price: 15.00
        );

        // Mocks
        $orderLineRepo = $this->createMock(EloquentOrderLineRepository::class);
        $orderLineService = $this->createMock(OrderLineService::class);

        // Expectations
        $orderLineRepo->method('exist')->with($command->getId())->willReturn(true);

        // Caso de uso
        $useCase = new UpdateOrderLineUseCase($orderLineRepo, $orderLineService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }


    public function test_update_order_line_fails_when_invalid_price_or_quantity()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Cantidad o precio no valido");

        $command = new UpdateOrderLineCommand(
            id: 1,
            quantity: -2, // Cantidad inválida
            name: "Producto Z",
            price: -5.00 // Precio inválido
        );

        // Mocks
        $orderLineRepo = $this->createMock(EloquentOrderLineRepository::class);
        $orderLineService = $this->createMock(OrderLineService::class);

        $orderLineRepo->method('exist')->with($command->getId())->willReturn(true);


        // Caso de uso
        $useCase = new UpdateOrderLineUseCase($orderLineRepo, $orderLineService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_update_order_line_handles_exception()
    {
        // Arrange
        $command = new UpdateOrderLineCommand(
            id: 1,
            quantity: 3,
            name: "Producto X",
            price: 20.00
        );

        // Mocks
        $orderLineRepo = $this->createMock(EloquentOrderLineRepository::class);
        $orderLineService = $this->createMock(OrderLineService::class);

        // Expectations
        $orderLineRepo->method('exist')->with($command->getId())->willReturn(true);
        $orderLineRepo->method('update')->willThrowException(new \Exception("Database error"));

        // Caso de uso
        $useCase = new UpdateOrderLineUseCase($orderLineRepo, $orderLineService);


        $useCase($command);

    }
}
