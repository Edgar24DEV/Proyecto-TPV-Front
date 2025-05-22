<?php

namespace Tests\Unit\Application\Payment\UseCases;

use App\Application\Payment\DTO\DeletePaymentCommand;
use App\Application\Payment\UseCases\DeletePaymentUseCase;
use App\Infrastructure\Repositories\EloquentPaymentRepository;
use PHPUnit\Framework\TestCase;

class DeletePaymentUseCaseTest extends TestCase
{
    public function test_delete_payment_successfully()
    {
        // Arrange
        $command = new DeletePaymentCommand(id: 1);

        // Mocks
        $paymentRepo = $this->createMock(EloquentPaymentRepository::class);

        // Expectations
        $paymentRepo->method('exist')->with($command->getId())->willReturn(true);
        $paymentRepo->method('delete')->with($command->getId())->willReturn(true);

        // Caso de uso
        $useCase = new DeletePaymentUseCase($paymentRepo);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertTrue($result);
    }

    public function test_delete_payment_fails_when_payment_not_found()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID de pago inválido");

        $command = new DeletePaymentCommand(id: 999); // ID inexistente

        // Mocks
        $paymentRepo = $this->createMock(EloquentPaymentRepository::class);

        // Expectations
        $paymentRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new DeletePaymentUseCase($paymentRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_delete_payment_fails_when_id_is_invalid()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID de pago inválido");

        $command = new DeletePaymentCommand(id: -1); // ID inválido

        // Mocks
        $paymentRepo = $this->createMock(EloquentPaymentRepository::class);

        // Caso de uso
        $useCase = new DeletePaymentUseCase($paymentRepo);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
