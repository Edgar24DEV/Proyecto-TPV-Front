<?php

namespace Tests\Unit\Application\Client\UseCases;

use App\Application\Client\DTO\DeleteClientCompanyCommand;
use App\Application\Client\UseCases\DeleteClientCompanyUseCase;
use App\Domain\Company\Entities\Client;
use App\Domain\Company\Services\ClientService;
use App\Infrastructure\Repositories\EloquentClientRepository;
use PHPUnit\Framework\TestCase;

class DeleteClientCompanyUseCaseTest extends TestCase
{
    public function test_delete_client_successfully()
    {
        // Arrange
        $command = new DeleteClientCompanyCommand(id: 1);

        // Mocks
        $clientRepo = $this->createMock(EloquentClientRepository::class);
        $clientService = $this->createMock(ClientService::class);

        // Mock client
        $client = new Client(
            id: 1,
            razon_social: "Empresa XYZ",
            direccion_fiscal: "Calle Falsa 123",
            cif: "ABC123",
            correo: "contacto@empresa.com",
            id_empresa: 1
        );

        // Expectations
        $clientRepo->method('find')->with($command->getId())->willReturn($client);
        $clientRepo->method('softDelete')->with($command->getId())->willReturn(true);

        // Caso de uso
        $useCase = new DeleteClientCompanyUseCase($clientRepo, $clientService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertTrue($result);
    }

    public function test_delete_client_fails_when_client_not_found()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Cliente no encontrado.");

        $command = new DeleteClientCompanyCommand(id: 999); // ID inexistente

        // Mocks
        $clientRepo = $this->createMock(EloquentClientRepository::class);
        $clientService = $this->createMock(ClientService::class);

        // Expectations
        $clientRepo->method('find')->with($command->getId())->willReturn(null);

        // Caso de uso
        $useCase = new DeleteClientCompanyUseCase($clientRepo, $clientService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
