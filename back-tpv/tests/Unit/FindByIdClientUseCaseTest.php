<?php

namespace Tests\Unit\Application\Client\UseCases;

use App\Application\Client\DTO\FindByIdClientCommand;
use App\Application\Client\UseCases\FindByIdClientUseCase;
use App\Domain\Company\Entities\Client;
use App\Domain\Company\Services\ClientService;
use App\Infrastructure\Repositories\EloquentClientRepository;
use PHPUnit\Framework\TestCase;

class FindByIdClientUseCaseTest extends TestCase
{
    public function test_find_client_successfully()
    {
        // Arrange
        $command = new FindByIdClientCommand(id: 1);

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
        $clientRepo->method('exist')->with($command->getId())->willReturn(true);
        $clientRepo->method('find')->with($command->getId())->willReturn($client);
        $clientService->method('showClientSimpleInfo')->with($client)->willReturn($client);

        // Caso de uso
        $useCase = new FindByIdClientUseCase($clientRepo, $clientService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Client::class, $result);
        $this->assertEquals(1, $result->id);
        $this->assertEquals("Empresa XYZ", $result->razon_social);
        $this->assertEquals("ABC123", $result->cif);
    }

    public function test_find_client_fails_when_invalid_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID cliente inválido");

        $command = new FindByIdClientCommand(id: -1); // ID inválido

        // Mocks
        $clientRepo = $this->createMock(EloquentClientRepository::class);
        $clientService = $this->createMock(ClientService::class);

        // Expectations
        $clientRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new FindByIdClientUseCase($clientRepo, $clientService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
