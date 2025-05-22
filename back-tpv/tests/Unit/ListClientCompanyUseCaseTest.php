<?php

namespace Tests\Unit\Application\Client\UseCases;

use App\Application\Client\DTO\ListClientCompanyCommand;
use App\Application\Client\UseCases\ListClientCompanyUseCase;
use App\Domain\Company\Services\ClientService;
use App\Infrastructure\Repositories\EloquentClientRepository;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class ListClientCompanyUseCaseTest extends TestCase
{
    public function test_list_clients_successfully()
    {
        // Arrange
        $command = new ListClientCompanyCommand(idCompany: 1);

        // Mocks
        $clientRepo = $this->createMock(EloquentClientRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $clientService = $this->createMock(ClientService::class);

        // Mock client list
        $clients = [
            ['id' => 1, 'razon_social' => 'Empresa A'],
            ['id' => 2, 'razon_social' => 'Empresa B'],
        ];

        $processedClients = new Collection([
            ['id' => 1, 'razon_social' => 'Empresa A - procesado'],
            ['id' => 2, 'razon_social' => 'Empresa B - procesado'],
        ]);

        // Expectations
        $companyRepo->method('exist')->with($command->getIdCompany())->willReturn(true);
        $clientRepo->method('findByCompany')->with($command->getIdCompany())->willReturn($clients);
        $clientService->method('showClientInfo')->with($clients)->willReturn($processedClients);

        // Caso de uso
        $useCase = new ListClientCompanyUseCase($clientRepo, $companyRepo, $clientService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertEquals("Empresa A - procesado", $result[0]['razon_social']);
        $this->assertEquals("Empresa B - procesado", $result[1]['razon_social']);
    }

    public function test_list_clients_fails_when_invalid_company_id()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID No existe");

        $command = new ListClientCompanyCommand(idCompany: 999); // ID inválido

        // Mocks
        $clientRepo = $this->createMock(EloquentClientRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $clientService = $this->createMock(ClientService::class);

        // Expectations
        $companyRepo->method('exist')->with($command->getIdCompany())->willReturn(false);

        // Caso de uso
        $useCase = new ListClientCompanyUseCase($clientRepo, $companyRepo, $clientService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
