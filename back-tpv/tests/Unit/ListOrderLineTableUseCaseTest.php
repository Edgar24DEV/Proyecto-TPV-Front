<?php

namespace Tests\Unit\Application\OrderLine\UseCases;

use App\Application\OrderLine\DTO\ListOrderLineTableCommand;
use App\Application\OrderLine\UseCases\ListOrderLineTableUseCase;
use App\Domain\Order\Entities\OrderLine;
use App\Domain\Order\Services\OrderLineService;
use App\Infrastructure\Repositories\EloquentOrderLineRepository;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class ListOrderLineTableUseCaseTest extends TestCase
{
    public function test_list_order_lines_successfully()
    {
        // Arrange
        $command = new ListOrderLineTableCommand(idOrderLine: 1);

        // Mocks
        $orderLineRepo = $this->createMock(EloquentOrderLineRepository::class);
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $orderLineService = new OrderLineService(); // Instancia real del servicio

        // Mock order lines data
        $orderLines = [
            new OrderLine(id: 1, idPedido: 1, idProducto: 5, cantidad: 3, precio: 20.00, nombre: "Producto X", observaciones: "Sin aderezo", estado: "pendiente"),
            new OrderLine(id: 2, idPedido: 1, idProducto: 6, cantidad: 2, precio: 15.00, nombre: "Producto Y", observaciones: "Extra salsa", estado: "pendiente")
        ];

        // Expectations
        $orderRepo->method('exist')->with($command->getIdOrderLine())->willReturn(true);
        $orderLineRepo->method('find')->with($command->getIdOrderLine())->willReturn($orderLines);

        // Caso de uso
        $useCase = new ListOrderLineTableUseCase($orderLineRepo, $orderLineService, $orderRepo);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertEquals("Producto X", $result[0]->nombre);
        $this->assertEquals("Producto Y", $result[1]->nombre);
        $this->assertEquals("Sin aderezo", $result[0]->observaciones);
        $this->assertEquals("Extra salsa", $result[1]->observaciones);
    }

    public function test_list_order_lines_fails_when_invalid_order_id()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID No existe");

        $command = new ListOrderLineTableCommand(idOrderLine: 999); // ID inexistente

        // Mocks
        $orderLineRepo = $this->createMock(EloquentOrderLineRepository::class);
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $orderLineService = new OrderLineService();

        // Expectations
        $orderRepo->method('exist')->with($command->getIdOrderLine())->willReturn(false);

        // Caso de uso
        $useCase = new ListOrderLineTableUseCase($orderLineRepo, $orderLineService, $orderRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
