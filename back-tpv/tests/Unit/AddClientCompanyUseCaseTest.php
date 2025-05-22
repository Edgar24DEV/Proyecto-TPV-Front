<?php

namespace Tests\Unit\Application\Client\UseCases;

use App\Application\Client\DTO\AddClientCompanyCommand;
use App\Application\Client\UseCases\AddClientCompanyUseCase;
use App\Domain\Company\Entities\Client;
use App\Domain\Company\Services\ClientService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentClientRepository;
use PHPUnit\Framework\TestCase;

class AddClientCompanyUseCaseTest extends TestCase
{
    public function test_add_client_to_company_successfully()
    {
        // Arrange
        $command = new AddClientCompanyCommand(
            razonSocial: "Empresa XYZ",
            cif: "ABC123",
            direccionFiscal: "Calle Falsa 123",
            correo: "contacto@empresa.com",
            idEmpresa: 1
        );

        // Mocks
        $clientRepo = $this->createMock(EloquentClientRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $clientService = $this->createMock(ClientService::class);

        // Mock created client
        $newClient = new Client(
            id: 1,
            razon_social: "Empresa XYZ",
            direccion_fiscal: "Calle Falsa 123",
            cif: "ABC123",
            correo: "contacto@empresa.com",
            id_empresa: 1
        );

        // Expectations
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(true);
        $clientRepo->method('create')->with($command)->willReturn($newClient);
        $clientService->method('showClientSimpleInfo')->with($newClient)->willReturn($newClient);

        // Caso de uso
        $useCase = new AddClientCompanyUseCase($clientRepo, $companyRepo, $clientService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Client::class, $result);
        $this->assertEquals("Empresa XYZ", $result->razon_social);
        $this->assertEquals("ABC123", $result->cif);
        $this->assertEquals("contacto@empresa.com", $result->correo);
    }

    public function test_add_client_fails_when_invalid_company_id()
    {
        // Arrange
        $command = new AddClientCompanyCommand(
            razonSocial: "Empresa XYZ",
            cif: "ABC123",
            direccionFiscal: "Calle Falsa 123",
            correo: "contacto@empresa.com",
            idEmpresa: -1
        );

        // Mocks
        $clientRepo = $this->createMock(EloquentClientRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $clientService = $this->createMock(ClientService::class);

        // Expectations
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(false);

        // Caso de uso
        $useCase = new AddClientCompanyUseCase($clientRepo, $companyRepo, $clientService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Client::class, $result);
        $this->assertEquals(-1, $result->id); // cliente inválido
    }

}
