<?php

namespace Tests\Unit\Application\Order\UseCases;

use App\Application\Order\DTO\GenerateTicketCommand;
use App\Application\Order\UseCases\GenerateTicketUseCase;
use App\Domain\Order\Entities\Order;
use App\Domain\Restaurant\Entities\Restaurant;
use App\Domain\Order\Services\TicketGeneratorServiceInterface;
use PHPUnit\Framework\TestCase;

class GenerateTicketUseCaseTest extends TestCase
{
    public function test_generate_ticket_successfully()
    {
        // Arrange
        $order = new Order(
            id: 1,
            estado: "cerrado",
            fechaInicio: new \DateTime("2024-05-14 12:00:00"),
            fechaFin: new \DateTime("2024-05-14 14:00:00"),
            comensales: 4,
            idMesa: 1
        );

        $lines = [
            ["id" => 1, "nombre" => "Producto A", "cantidad" => 2, "precio" => 10.00],
            ["id" => 2, "nombre" => "Producto B", "cantidad" => 1, "precio" => 5.00]
        ];

        $total = 25.00;
        $iva = 5.00;

        $restaurant = new Restaurant(
            id: 1,
            nombre: "Restaurante X",
            direccion: "Calle Principal 123",
            telefono: "123456789",
            contrasenya: "password123",
            direccionFiscal: "Calle Fiscal 456",
            cif: "A12345678",
            razonSocial: "Restaurante X S.A.",
            idEmpresa: 10
        );

        $command = new GenerateTicketCommand($order, $lines, $total, $iva, $restaurant);

        // Mocks
        $ticketGeneratorService = $this->createMock(TicketGeneratorServiceInterface::class);

        // Expectations
        $ticketGeneratorService->method('generate')->with(
            $command->order,
            $command->lines,
            $command->total,
            $command->iva,
            $command->restaurant
        )->willReturn("https://example.com/ticket_12345.pdf");

        // Caso de uso
        $useCase = new GenerateTicketUseCase($ticketGeneratorService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertIsString($result);
        $this->assertEquals("https://example.com/ticket_12345.pdf", $result);
    }
}
