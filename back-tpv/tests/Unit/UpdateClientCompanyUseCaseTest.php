<?php

namespace Tests\Unit\Application\Client\UseCases;

use App\Application\Client\DTO\UpdateClientCompanyCommand;
use App\Application\Client\UseCases\UpdateClientCompanyUseCase;
use App\Domain\Company\Entities\Client;
use App\Domain\Company\Services\ClientService;
use App\Infrastructure\Repositories\EloquentClientRepository;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Log;

class UpdateClientCompanyUseCaseTest extends TestCase
{
    public function test_update_client_successfully()
    {
        // Arrange
        $command = new UpdateClientCompanyCommand(
            id: 1,
            razonSocial: "Empresa XYZ Actualizada",
            cif: "ABC123UPDATED",
            direccionFiscal: "Nueva Dirección",
            correo: "nuevo@empresa.com",
            idEmpresa: 2
        );

        // Mocks
        $clientRepo = $this->createMock(EloquentClientRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $clientService = $this->createMock(ClientService::class);

        // Mock updated client
        $updatedClient = new Client(
            id: 1,
            razon_social: "Empresa XYZ Actualizada",
            direccion_fiscal: "Nueva Dirección",
            cif: "ABC123UPDATED",
            correo: "nuevo@empresa.com",
            id_empresa: 2
        );

        // Expectations
        $clientRepo->method('exist')->with($command->getId())->willReturn(true);
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(true);
        $clientRepo->method('update')->with($command)->willReturn($updatedClient);
        $clientService->method('showClientSimpleInfo')->with($updatedClient)->willReturn($updatedClient);

        // Caso de uso
        $useCase = new UpdateClientCompanyUseCase($clientRepo, $companyRepo, $clientService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Client::class, $result);
        $this->assertEquals("Empresa XYZ Actualizada", $result->razon_social);
        $this->assertEquals("ABC123UPDATED", $result->cif);
        $this->assertEquals("nuevo@empresa.com", $result->correo);
    }

    public function test_update_client_returns_placeholder_when_client_not_found()
    {
        // Arrange
        $command = new UpdateClientCompanyCommand(
            id: 999,
            razonSocial: "Empresa XYZ",
            cif: "ABC123",
            direccionFiscal: "Calle Falsa 123",
            correo: "contacto@empresa.com",
            idEmpresa: 1
        );

        $clientRepo = $this->createMock(EloquentClientRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $clientService = $this->createMock(ClientService::class);

        $clientRepo->method('exist')->with($command->getId())->willReturn(false);

        $useCase = new UpdateClientCompanyUseCase($clientRepo, $companyRepo, $clientService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Client::class, $result);
        $this->assertEquals(-1, $result->id);
    }

    public function test_update_client_returns_placeholder_when_invalid_company_id()
    {
        // Arrange
        $command = new UpdateClientCompanyCommand(
            id: 1,
            razonSocial: "Empresa XYZ",
            cif: "ABC123",
            direccionFiscal: "Calle Falsa 123",
            correo: "contacto@empresa.com",
            idEmpresa: 999 // ID empresa inexistente
        );

        // Mocks
        $clientRepo = $this->createMock(EloquentClientRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $clientService = $this->createMock(ClientService::class);

        // Expectations
        $clientRepo->method('exist')->with($command->getId())->willReturn(true);
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(false);

        // Caso de uso
        $useCase = new UpdateClientCompanyUseCase($clientRepo, $companyRepo, $clientService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Client::class, $result);
        $this->assertEquals(-1, $result->id); // cliente placeholder
        $this->assertEquals('', $result->direccion_fiscal); // según tu implementación
    }

}
