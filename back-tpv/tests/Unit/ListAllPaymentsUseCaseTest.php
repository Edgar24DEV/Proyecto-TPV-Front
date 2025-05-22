<?php

namespace Tests\Unit\Application\Payment\UseCases;

use App\Application\Payment\DTO\ListAllPaymentsCommand;
use App\Application\Payment\UseCases\ListAllPaymentsUseCase;
use App\Domain\Order\Entities\Payment;
use App\Domain\Order\Services\PaymentService;
use App\Infrastructure\Repositories\EloquentPaymentRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class ListAllPaymentsUseCaseTest extends TestCase
{
    public function test_list_all_payments_successfully()
    {
        // Arrange
        $command = new ListAllPaymentsCommand(idRestaurante: 1);

        // Mocks
        $paymentRepo = $this->createMock(EloquentPaymentRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $paymentService = $this->createMock(PaymentService::class);

        // Mock payment data
        $payments = [
            new Payment(id: 1, tipo: "Tarjeta", cantidad: 50.00, fecha: "2024-05-14", idPedido: 10),
            new Payment(id: 2, tipo: "Efectivo", cantidad: 20.00, fecha: "2024-05-14", idPedido: 11)
        ];

        $processedPayments = new Collection([
            new Payment(id: 1, tipo: "Tarjeta - Procesado", cantidad: 50.00, fecha: "2024-05-14", idPedido: 10),
            new Payment(id: 2, tipo: "Efectivo - Procesado", cantidad: 20.00, fecha: "2024-05-14", idPedido: 11)
        ]);

        // Expectations
        $restaurantRepo->method('exist')->with($command->getIdRestaurant())->willReturn(true);
        $paymentRepo->method('findAll')->with($command->getIdRestaurant())->willReturn($payments);
        $paymentService->method('showPaymentInfo')->with($payments)->willReturn($processedPayments);

        // Caso de uso
        $useCase = new ListAllPaymentsUseCase($paymentRepo, $restaurantRepo, $paymentService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertEquals("Tarjeta - Procesado", $result[0]->tipo);
        $this->assertEquals("Efectivo - Procesado", $result[1]->tipo);
    }

    public function test_list_all_payments_fails_when_invalid_restaurant_id()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID No existe");

        $command = new ListAllPaymentsCommand(idRestaurante: 999); // Restaurante inexistente

        // Mocks
        $paymentRepo = $this->createMock(EloquentPaymentRepository::class);
        $restaurantRepo = $this->createMock(EloquentRestaurantRepository::class);
        $paymentService = $this->createMock(PaymentService::class);

        // Expectations
        $restaurantRepo->method('exist')->with($command->getIdRestaurant())->willReturn(false);

        // Caso de uso
        $useCase = new ListAllPaymentsUseCase($paymentRepo, $restaurantRepo, $paymentService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
