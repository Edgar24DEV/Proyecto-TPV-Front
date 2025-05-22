<?php

namespace Tests\Unit\Application\Order\UseCases;

use App\Application\Order\DTO\GenerateBillCommand;
use App\Application\Order\UseCases\GenerateBillUseCase;
use App\Domain\Company\Entities\Client;
use App\Domain\Order\Entities\Order;
use App\Domain\Order\Entities\Payment;
use App\Domain\Restaurant\Entities\Restaurant;
use App\Infrastructure\Pdf\DompdfBillGeneratorService;
use PHPUnit\Framework\TestCase;

class GenerateBillUseCaseTest extends TestCase
{
    public function test_generate_bill_successfully()
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

        $client = new Client(
            id: 1,
            razon_social: "Cliente Y S.L.",
            cif: "B87654321",
            direccion_fiscal: "Avenida Cliente 789",
            correo: "cliente@example.com",
            id_empresa: 20
        );

        $payment = new Payment(
            id: 1,
            tipo: "Tarjeta",
            cantidad: 25.00,
            fecha: "2024-05-14",
            idPedido: 1,
            idCliente: 1,
            razonSocial: $client->getRazonSocial(),
            CIF: $client->getCif(),
            nFactura: "F00123",
            correo: $client->getCorreo(),
            direccionFiscal: $client->getDireccionFiscal()
        );

        $command = new GenerateBillCommand($order, $lines, $total, $iva, $restaurant, $client, $payment);

        // Mocks
        $billGeneratorService = $this->createMock(DompdfBillGeneratorService::class);

        // Expectations
        $billGeneratorService->method('generate')->with(
            $command->order,
            $command->lines,
            $command->total,
            $command->iva,
            $command->restaurant,
            $command->client,
            $command->payment
        )->willReturn("https://example.com/bill_F00123.pdf");

        // Caso de uso
        $useCase = new GenerateBillUseCase($billGeneratorService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertIsString($result);
        $this->assertEquals("https://example.com/bill_F00123.pdf", $result);
    }
}
