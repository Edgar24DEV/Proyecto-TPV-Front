<?php

namespace Tests\Unit\Application\Company\UseCases;

use App\Application\Company\DTO\LoginCompanyCommand;
use App\Application\Company\UseCases\LoginCompanyUseCase;
use App\Domain\Company\Entities\Company;
use App\Domain\Company\Services\CompanyService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Log;

class LoginCompanyUseCaseTest extends TestCase
{
    public function test_login_company_successfully()
    {
        // Arrange
        $command = new LoginCompanyCommand(nombre: "Empresa X", contrasenya: "securepass");

        // Mocks
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $companyService = $this->createMock(CompanyService::class);

        // Mock logged-in company
        $loggedCompany = new Company(
            id: 1,
            nombre: "Empresa X",
            direccionFiscal: "Calle Falsa 123",
            CIF: "ABC123",
            razonSocial: "Empresa X S.A.",
            telefono: "123456789",
            correo: "contacto@empresa.com",
            contrasenya: "securepass"
        );

        // Expectations
        $companyRepo->method('login')->with($command)->willReturn($loggedCompany);
        $companyService->method('showCompanyInfoSimple')->with($loggedCompany)->willReturn($loggedCompany);

        // Caso de uso
        $useCase = new LoginCompanyUseCase($companyRepo, $companyService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Company::class, $result);
        $this->assertEquals("Empresa X", $result->nombre);
        $this->assertEquals("ABC123", $result->CIF);
        $this->assertEquals("contacto@empresa.com", $result->correo);
    }

    public function test_login_company_handles_exception()
    {
        // Arrange
        $command = new LoginCompanyCommand(nombre: "Empresa X", contrasenya: "securepass");

        // Mocks
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $companyService = $this->createMock(CompanyService::class);

        // Expectations
        $companyRepo->method('login')->with($command)->willThrowException(new \Exception("Database error"));

        // Caso de uso
        $useCase = new LoginCompanyUseCase($companyRepo, $companyService);

        // Act & Assert
        $useCase($command);

    }
}
