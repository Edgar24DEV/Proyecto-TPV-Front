<?php

namespace Tests\Unit\Application\Payment\UseCases;

use App\Application\Payment\DTO\UpdatePaymentBillCommand;
use App\Application\Payment\UseCases\UpdatePaymentBillUseCase;
use App\Domain\Order\Entities\Payment;
use App\Infrastructure\Repositories\EloquentPaymentRepository;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use App\Infrastructure\Repositories\EloquentUpdateBillRepository;
use PHPUnit\Framework\TestCase;

class UpdatePaymentBillUseCaseTest extends TestCase
{
    public function test_update_payment_bill_successfully()
    {
        // Arrange
        $command = new UpdatePaymentBillCommand(
            idPedido: 1,
            idCliente: 10,
            razonSocial: "Empresa S.A.",
            CIF: "A12345678",
            nFactura: "F00123",
            correo: "cliente@example.com",
            direccionFiscal: "Calle Falsa 123"
        );

        // Mocks
        $paymentRepo = $this->createMock(EloquentPaymentRepository::class);
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $updateBillRepo = $this->createMock(EloquentUpdateBillRepository::class);

        // Mock payment data
        $updatedPayment = new Payment(
            id: 1,
            tipo: "Tarjeta",
            cantidad: 100.50,
            fecha: "2024-05-14",
            idPedido: 1,
            idCliente: 10,
            razonSocial: "Empresa S.A.",
            CIF: "A12345678",
            nFactura: "F00123",
            correo: "cliente@example.com",
            direccionFiscal: "Calle Falsa 123"
        );

        // Expectations
        $orderRepo->method('exist')->with($command->getIdPedido())->willReturn(true);
        $paymentRepo->method('updateBill')->with($command)->willReturn($updatedPayment);

        // Caso de uso
        $useCase = new UpdatePaymentBillUseCase($paymentRepo, $orderRepo, $updateBillRepo);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Payment::class, $result);
        $this->assertEquals("F00123", $result->nFactura);
        $this->assertEquals("A12345678", $result->CIF);
    }

    public function test_update_payment_bill_fails_when_order_not_found()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID pedido inválido");

        $command = new UpdatePaymentBillCommand(
            idPedido: 999, // Pedido inexistente
            idCliente: 10,
            razonSocial: "Empresa S.A.",
            CIF: "A12345678",
            nFactura: "F00123",
            correo: "cliente@example.com",
            direccionFiscal: "Calle Falsa 123"
        );

        // Mocks
        $paymentRepo = $this->createMock(EloquentPaymentRepository::class);
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $updateBillRepo = $this->createMock(EloquentUpdateBillRepository::class);

        // Expectations
        $orderRepo->method('exist')->with($command->getIdPedido())->willReturn(false);

        // Caso de uso
        $useCase = new UpdatePaymentBillUseCase($paymentRepo, $orderRepo, $updateBillRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_update_payment_bill_fails_when_CIF_is_missing()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("CIF es necesario para generar el número de factura");

        $command = new UpdatePaymentBillCommand(
            idPedido: 1, // Asegurar que el ID del pedido es válido
            idCliente: 1,
            razonSocial: "Empresa S.A.",
            CIF: null, // CIF inválido
            nFactura: "F00123",
            correo: "cliente@example.com",
            direccionFiscal: "Calle Falsa 123"
        );

        // Mocks
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $paymentRepo = $this->createMock(EloquentPaymentRepository::class);
        $updateBillRepo = $this->createMock(EloquentUpdateBillRepository::class);

        // Expectations
        $orderRepo->method('exist')->with($command->getIdPedido())->willReturn(true); // Pedido existe

        // Caso de uso
        $useCase = new UpdatePaymentBillUseCase($paymentRepo, $orderRepo, $updateBillRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

}
