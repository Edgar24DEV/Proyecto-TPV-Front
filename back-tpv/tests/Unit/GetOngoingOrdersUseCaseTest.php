<?php

namespace Tests\Unit\Application\Order\UseCases;

use App\Application\Order\DTO\GetOngoingOrdersCommand;
use App\Application\Order\UseCases\GetOngoingOrdersUseCase;
use App\Domain\Order\Entities\Order;
use App\Domain\Order\Services\OrderService;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class GetOngoingOrdersUseCaseTest extends TestCase
{
    public function test_get_ongoing_orders_successfully()
    {
        // Arrange
        $command = new GetOngoingOrdersCommand(idRestaurante: 1);

        // Mocks
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $orderService = $this->createMock(OrderService::class);

        // Mock ongoing orders
        $orders = [
            new Order(id: 1, estado: "activo", fechaInicio: new \DateTime("2024-05-14"), fechaFin: null, comensales: 4, idMesa: 1),
            new Order(id: 2, estado: "pendiente", fechaInicio: new \DateTime("2024-05-14"), fechaFin: null, comensales: 2, idMesa: 2)
        ];

        $processedOrders = new Collection([
            new Order(id: 1, estado: "activo - procesado", fechaInicio: new \DateTime("2024-05-14"), fechaFin: null, comensales: 4, idMesa: 1),
            new Order(id: 2, estado: "pendiente - procesado", fechaInicio: new \DateTime("2024-05-14"), fechaFin: null, comensales: 2, idMesa: 2)
        ]);

        // Expectations
        $restaurantRepo->method('exist')->with($command->getIdRestaurante())->willReturn(true);
        $orderRepo->method('getOngoingOrders')->with($command)->willReturn($orders);
        $orderService->method('showOrderInfo')->with($orders)->willReturn($processedOrders);

        // Caso de uso
        $useCase = new GetOngoingOrdersUseCase($orderRepo, $restaurantRepo, $orderService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertEquals("activo - procesado", $result[0]->estado);
        $this->assertEquals("pendiente - procesado", $result[1]->estado);
    }

    public function test_get_ongoing_orders_fails_when_invalid_restaurant_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID restaurante inválido");

        $command = new GetOngoingOrdersCommand(idRestaurante: -1); // ID inválido

        // Mocks
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $orderService = $this->createMock(OrderService::class);

        // Expectations
        $restaurantRepo->method('exist')->with($command->getIdRestaurante())->willReturn(false);

        // Caso de uso
        $useCase = new GetOngoingOrdersUseCase($orderRepo, $restaurantRepo, $orderService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
