<?php

namespace Tests\Unit\Application\Payment\UseCases;

use App\Application\Payment\DTO\ListPaymentsOrderCommand;
use App\Application\Payment\UseCases\ListPaymentsOrderUseCase;
use App\Domain\Order\Entities\Payment;
use App\Domain\Order\Services\PaymentService;
use App\Infrastructure\Repositories\EloquentPaymentRepository;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class ListPaymentsOrderUseCaseTest extends TestCase
{
    public function test_list_payments_order_successfully()
    {
        // Arrange
        $command = new ListPaymentsOrderCommand(idPedido: 1);

        // Mocks
        $paymentRepo = $this->createMock(EloquentPaymentRepository::class);
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $paymentService = $this->createMock(PaymentService::class);

        // Mock payment data
        $payments = [
            new Payment(id: 1, tipo: "Tarjeta", cantidad: 50.00, fecha: "2024-05-14", idPedido: 1),
            new Payment(id: 2, tipo: "Efectivo", cantidad: 20.00, fecha: "2024-05-14", idPedido: 1)
        ];

        $processedPayments = new Collection([
            new Payment(id: 1, tipo: "Tarjeta - Procesado", cantidad: 50.00, fecha: "2024-05-14", idPedido: 1),
            new Payment(id: 2, tipo: "Efectivo - Procesado", cantidad: 20.00, fecha: "2024-05-14", idPedido: 1)
        ]);

        // Expectations
        $orderRepo->method('exist')->with($command->getIdPedido())->willReturn(true);
        $paymentRepo->method('findByOrder')->with($command->getIdPedido())->willReturn($payments);
        $paymentService->method('showPaymentInfo')->with($payments)->willReturn($processedPayments);

        // Caso de uso
        $useCase = new ListPaymentsOrderUseCase($paymentRepo, $orderRepo, $paymentService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertEquals("Tarjeta - Procesado", $result[0]->tipo);
        $this->assertEquals("Efectivo - Procesado", $result[1]->tipo);
    }

    public function test_list_payments_order_fails_when_invalid_order_id()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID No existe");

        $command = new ListPaymentsOrderCommand(idPedido: 999); // Pedido inexistente

        // Mocks
        $paymentRepo = $this->createMock(EloquentPaymentRepository::class);
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $paymentService = $this->createMock(PaymentService::class);

        // Expectations
        $orderRepo->method('exist')->with($command->getIdPedido())->willReturn(false);

        // Caso de uso
        $useCase = new ListPaymentsOrderUseCase($paymentRepo, $orderRepo, $paymentService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
