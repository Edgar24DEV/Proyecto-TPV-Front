<?php

namespace Tests\Unit\Application\Payment\UseCases;

use App\Application\Payment\DTO\AddPaymentCommand;
use App\Application\Payment\UseCases\AddPaymentUseCase;
use App\Domain\Order\Entities\Payment;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use App\Infrastructure\Repositories\EloquentPaymentRepository;
use PHPUnit\Framework\TestCase;

class AddPaymentUseCaseTest extends TestCase
{
    public function test_add_payment_successfully()
    {
        // Arrange
        $command = new AddPaymentCommand(
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

        // Mocks
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $paymentRepo = $this->createMock(EloquentPaymentRepository::class);

        // Mock payment data
        $payment = new Payment(
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
        $paymentRepo->method('save')->with($command)->willReturn($payment);

        // Caso de uso
        $useCase = new AddPaymentUseCase($orderRepo, $paymentRepo);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Payment::class, $result);
        $this->assertEquals("Tarjeta", $result->tipo);
        $this->assertEquals(100.50, $result->cantidad);
        $this->assertEquals("2024-05-14", $result->fecha);
        $this->assertEquals("Empresa S.A.", $result->razonSocial);
        $this->assertEquals("A12345678", $result->CIF);
        $this->assertEquals("F00123", $result->nFactura);
        $this->assertEquals("cliente@example.com", $result->correo);
        $this->assertEquals("Calle Falsa 123", $result->direccionFiscal);
    }

    public function test_add_payment_fails_when_order_not_found()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("No se encuentra el pedido del que se quiere hacer el pago");

        $command = new AddPaymentCommand(
            tipo: "Efectivo",
            cantidad: 50.00,
            fecha: "2024-05-14",
            idPedido: 999, // Pedido inexistente
            idCliente: 10,
            razonSocial: "Empresa S.A.",
            CIF: "A12345678",
            nFactura: "F00123",
            correo: "cliente@example.com",
            direccionFiscal: "Calle Falsa 123"
        );

        // Mocks
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $paymentRepo = $this->createMock(EloquentPaymentRepository::class);

        // Expectations
        $orderRepo->method('exist')->with($command->getIdPedido())->willReturn(false);

        // Caso de uso
        $useCase = new AddPaymentUseCase($orderRepo, $paymentRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_add_payment_fails_when_invalid_payment_amount()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("El total debe ser un valor positivo");

        $command = new AddPaymentCommand(
            tipo: "Transferencia",
            cantidad: -10.00, // Valor inválido
            fecha: "2024-05-14",
            idPedido: 1,
            idCliente: 10,
            razonSocial: "Empresa S.A.",
            CIF: "A12345678",
            nFactura: "F00123",
            correo: "cliente@example.com",
            direccionFiscal: "Calle Falsa 123"
        );

        // Mocks
        $orderRepo = $this->createMock(EloquentOrderRepository::class);
        $paymentRepo = $this->createMock(EloquentPaymentRepository::class);

        // Caso de uso
        $useCase = new AddPaymentUseCase($orderRepo, $paymentRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
