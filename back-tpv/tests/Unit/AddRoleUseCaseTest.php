<?php

namespace Tests\Unit;

use App\Application\Role\UseCases\AddRoleUseCase;
use App\Application\Role\DTO\AddRoleCommand;
use App\Domain\Employee\Entities\Role;
use App\Domain\Employee\Services\RoleService;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use PHPUnit\Framework\TestCase;

class AddRoleUseCaseTest extends TestCase
{
    public function test_add_role_successfully()
    {
        // Arrange
        $command = new AddRoleCommand(
            rol: "Admin",
            productos: true,
            categorias: true,
            tpv: true,
            usuarios: true,
            mesas: true,
            restaurante: true,
            clientes: true,
            empresa: true,
            pago: true,
            idEmpresa: 1
        );

        // Mocks
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $roleService = $this->createMock(RoleService::class);

        // Mock role data
        $newRole = new Role(
            id: 1,
            rol: "Admin",
            productos: true,
            categorias: true,
            tpv: true,
            usuarios: true,
            mesas: true,
            restaurante: true,
            clientes: true,
            empresa: true,
            pago: true,
            idEmpresa: 1
        );

        $roleInfo = new Role(
            id: 1,
            rol: "Admin Info",
            productos: true,
            categorias: true,
            tpv: true,
            usuarios: true,
            mesas: true,
            restaurante: true,
            clientes: true,
            empresa: true,
            pago: true,
            idEmpresa: 1
        );

        // Expectations
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(true);
        $roleRepo->method('existName')->with($command->getRol(), $command->getIdEmpresa())->willReturn(false);
        $roleRepo->method('create')->with($command)->willReturn($newRole);
        $roleService->method('showRoleInfoSimple')->with($newRole)->willReturn($roleInfo);

        // Caso de uso
        $useCase = new AddRoleUseCase($roleRepo, $companyRepo, $roleService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Role::class, $result);
        $this->assertEquals("Admin Info", $result->rol);
    }

    public function test_add_role_fails_when_invalid_company_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID empresa inválido");

        $command = new AddRoleCommand(
            rol: "Admin",
            productos: true,
            categorias: true,
            tpv: true,
            usuarios: true,
            mesas: true,
            restaurante: true,
            clientes: true,
            empresa: true,
            pago: true,
            idEmpresa: 0 // ID inválido
        );

        // Mocks
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $roleService = $this->createMock(RoleService::class);

        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(false);

        // Caso de uso
        $useCase = new AddRoleUseCase($roleRepo, $companyRepo, $roleService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_add_role_fails_when_role_already_exists()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("El rol ya existe");

        $command = new AddRoleCommand(
            rol: "Admin",
            productos: true,
            categorias: true,
            tpv: true,
            usuarios: true,
            mesas: true,
            restaurante: true,
            clientes: true,
            empresa: true,
            pago: true,
            idEmpresa: 1
        );

        // Mocks
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $roleService = $this->createMock(RoleService::class);

        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(true);
        $roleRepo->method('existName')->with($command->getRol(), $command->getIdEmpresa())->willReturn(true);

        // Caso de uso
        $useCase = new AddRoleUseCase($roleRepo, $companyRepo, $roleService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
