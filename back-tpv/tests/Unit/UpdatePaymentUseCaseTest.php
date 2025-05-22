<?php

namespace Tests\Unit\Application\Payment\UseCases;

use App\Application\Payment\DTO\UpdatePaymentCommand;
use App\Application\Payment\UseCases\UpdatePaymentUseCase;
use App\Domain\Order\Entities\Payment;
use App\Infrastructure\Repositories\EloquentBillRepository;
use App\Infrastructure\Repositories\EloquentPaymentRepository;
use PHPUnit\Framework\TestCase;

class UpdatePaymentUseCaseTest extends TestCase
{
    public function test_update_payment_successfully()
    {
        // Arrange
        $command = new UpdatePaymentCommand(
            idPago: 1,
            idCliente: 10,
            razonSocial: "Empresa S.A.",
            CIF: "A12345678",
            nFactura: "F00123",
            correo: "cliente@example.com",
            direccionFiscal: "Calle Falsa 123"
        );

        // Mocks
        $paymentRepo = $this->createMock(EloquentPaymentRepository::class);
        $billRepo = $this->createMock(EloquentBillRepository::class);

        // Mock updated payment
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
        $paymentRepo->method('exist')->with($command->getIdPago())->willReturn(true);
        $paymentRepo->method('update')->with($command)->willReturn($updatedPayment);

        // Caso de uso
        $useCase = new UpdatePaymentUseCase($paymentRepo, $billRepo);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Payment::class, $result);
        $this->assertEquals("F00123", $result->nFactura);
        $this->assertEquals("A12345678", $result->CIF);
        $this->assertEquals(100.50, $result->cantidad);
        $this->assertEquals("Tarjeta", $result->tipo);
        $this->assertEquals("2024-05-14", $result->fecha);
    }

    public function test_update_payment_generates_invoice_number_when_missing()
    {
        // Arrange
        $command = new UpdatePaymentCommand(
            idPago: 1,
            idCliente: 10,
            razonSocial: "Empresa S.A.",
            CIF: "A12345678",
            nFactura: null, // No tiene factura
            correo: "cliente@example.com",
            direccionFiscal: "Calle Falsa 123"
        );

        // Mocks
        $paymentRepo = $this->createMock(EloquentPaymentRepository::class);
        $billRepo = $this->createMock(EloquentBillRepository::class);

        // Mock generated invoice number
        $billRepo->method('generateOnlyNumber')->with($command->getCIF())->willReturn("INV12345");

        // Mock updated payment
        $updatedPayment = new Payment(
            id: 1,
            tipo: "Tarjeta",
            cantidad: 100.50,
            fecha: "2024-05-14",
            idPedido: 1,
            idCliente: 10,
            razonSocial: "Empresa S.A.",
            CIF: "A12345678",
            nFactura: "INV12345", // Factura generada
            correo: "cliente@example.com",
            direccionFiscal: "Calle Falsa 123"
        );

        // Expectations
        $paymentRepo->method('exist')->with($command->getIdPago())->willReturn(true);
        $paymentRepo->method('update')->willReturn($updatedPayment);

        // Caso de uso
        $useCase = new UpdatePaymentUseCase($paymentRepo, $billRepo);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Payment::class, $result);
        $this->assertEquals("INV12345", $result->nFactura);
        $this->assertEquals("A12345678", $result->CIF);
        $this->assertEquals(100.50, $result->cantidad);
        $this->assertEquals("Tarjeta", $result->tipo);
        $this->assertEquals("2024-05-14", $result->fecha);
    }

    public function test_update_payment_fails_when_payment_not_found()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID pago inválido");

        $command = new UpdatePaymentCommand(
            idPago: 999, // Pago inexistente
            idCliente: 10,
            razonSocial: "Empresa S.A.",
            CIF: "A12345678",
            nFactura: "F00123",
            correo: "cliente@example.com",
            direccionFiscal: "Calle Falsa 123"
        );

        // Mocks
        $paymentRepo = $this->createMock(EloquentPaymentRepository::class);
        $billRepo = $this->createMock(EloquentBillRepository::class);

        // Expectations
        $paymentRepo->method('exist')->with($command->getIdPago())->willReturn(false);

        // Caso de uso
        $useCase = new UpdatePaymentUseCase($paymentRepo, $billRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_update_payment_fails_when_CIF_is_missing()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("CIF es necesario para generar el número de factura");

        $command = new UpdatePaymentCommand(
            idPago: 1,
            idCliente: 10,
            razonSocial: "Empresa S.A.",
            CIF: null, // CIF inválido
            nFactura: "F00123",
            correo: "cliente@example.com",
            direccionFiscal: "Calle Falsa 123"
        );

        // Mocks
        $paymentRepo = $this->createMock(EloquentPaymentRepository::class);
        $billRepo = $this->createMock(EloquentBillRepository::class);

        $paymentRepo->method('exist')->with($command->getIdPago())->willReturn(true);
        // Caso de uso
        $useCase = new UpdatePaymentUseCase($paymentRepo, $billRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
