<?php

namespace Tests\Unit\Application\Client\UseCases;

use App\Application\Client\DTO\FindClientCifCommand;
use App\Application\Client\UseCases\FindClientCifUseCase;
use App\Domain\Company\Entities\Client;
use App\Domain\Company\Services\ClientService;
use App\Infrastructure\Repositories\EloquentClientRepository;
use PHPUnit\Framework\TestCase;

class FindClientCifUseCaseTest extends TestCase
{
    public function test_find_client_by_cif_successfully()
    {
        // Arrange
        $command = new FindClientCifCommand(cif: "ABC123");

        // Mocks
        $clientRepo = $this->createMock(EloquentClientRepository::class);
        $clientService = $this->createMock(ClientService::class);

        // Mock existing client
        $client = new Client(
            id: 1,
            razon_social: "Empresa XYZ",
            direccion_fiscal: "Calle Falsa 123",
            cif: "ABC123",
            correo: "contacto@empresa.com",
            id_empresa: 1
        );

        // Expectations
        $clientRepo->method('findByCif')->with($command->getCif())->willReturn($client);
        $clientService->method('showClientSimpleInfo')->with($client)->willReturn($client);

        // Caso de uso
        $useCase = new FindClientCifUseCase($clientRepo, $clientService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Client::class, $result);
        $this->assertEquals("ABC123", $result->cif);
        $this->assertEquals("Empresa XYZ", $result->razon_social);
    }

    public function test_find_client_by_cif_fails_when_client_not_found()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Cliente no encontrado.");

        $command = new FindClientCifCommand(cif: "XYZ999"); // CIF inexistente

        // Mocks
        $clientRepo = $this->createMock(EloquentClientRepository::class);
        $clientService = $this->createMock(ClientService::class);

        // Expectations
        $clientRepo->method('findByCif')->with($command->getCif())->willThrowException(new \Exception("Cliente no encontrado."));

        // Caso de uso
        $useCase = new FindClientCifUseCase($clientRepo, $clientService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
