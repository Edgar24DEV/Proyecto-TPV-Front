<?php

namespace Tests\Unit\Application\Order\UseCases;

use App\Application\Order\DTO\GetCompanyOrdersCommand;
use App\Application\Order\UseCases\GetCompanyOrdersUseCase;
use App\Domain\Order\Entities\Order;
use App\Domain\Order\Services\OrderService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class GetCompanyOrdersUseCaseTest extends TestCase
{
    public function test_get_company_orders_successfully()
    {
        // Arrange
        $command = new GetCompanyOrdersCommand(idEmpresa: 1);

        // Mocks
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $orderService = $this->createMock(OrderService::class);

        // Mock company orders
        $orders = [
            new Order(id: 1, estado: "activo", fechaInicio: new \DateTime("2024-05-14"), fechaFin: null, comensales: 4, idMesa: 1),
            new Order(id: 2, estado: "pendiente", fechaInicio: new \DateTime("2024-05-14"), fechaFin: null, comensales: 2, idMesa: 2)
        ];

        $processedOrders = new Collection([
            new Order(id: 1, estado: "activo - procesado", fechaInicio: new \DateTime("2024-05-14"), fechaFin: null, comensales: 4, idMesa: 1),
            new Order(id: 2, estado: "pendiente - procesado", fechaInicio: new \DateTime("2024-05-14"), fechaFin: null, comensales: 2, idMesa: 2)
        ]);

        // Expectations
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(true);
        $orderRepo->method('getCompanyOrders')->with($command)->willReturn($orders);
        $orderService->method('showOrderInfo')->with($orders)->willReturn($processedOrders);

        // Caso de uso
        $useCase = new GetCompanyOrdersUseCase($orderRepo, $companyRepo, $orderService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertEquals("activo - procesado", $result[0]->estado);
        $this->assertEquals("pendiente - procesado", $result[1]->estado);
    }

    public function test_get_company_orders_fails_when_invalid_company_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID restaurante inválido");

        $command = new GetCompanyOrdersCommand(idEmpresa: -1); // ID inválido

        // Mocks
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $orderService = $this->createMock(OrderService::class);

        // Expectations
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(false);

        // Caso de uso
        $useCase = new GetCompanyOrdersUseCase($orderRepo, $companyRepo, $orderService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
