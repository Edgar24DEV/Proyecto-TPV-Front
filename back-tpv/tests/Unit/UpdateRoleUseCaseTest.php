<?php

namespace Tests\Unit\Application\Role\UseCases;

use App\Application\Role\DTO\UpdateRoleCommand;
use App\Application\Role\UseCases\UpdateRoleUseCase;
use App\Domain\Employee\Entities\Role;
use App\Domain\Employee\Services\RoleService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use PHPUnit\Framework\TestCase;

class UpdateRoleUseCaseTest extends TestCase
{
    public function test_update_role_successfully()
    {
        // Arrange
        $command = new UpdateRoleCommand(
            id: 1,
            idEmpresa: 1,
            rol: "Admin",
            productos: true,
            categorias: true,
            tpv: true,
            usuarios: true,
            mesas: true,
            restaurante: true,
            clientes: true,
            empresa: true,
            pago: true
        );

        // Mocks
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $roleService = $this->createMock(RoleService::class);

        // Mock role data
        $updatedRole = new Role(
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
        $roleRepo->method('exist')->with($command->getId())->willReturn(true);
        $roleRepo->method('existwithCompany')->with($command->getId(), $command->getIdEmpresa())->willReturn(true);
        $roleRepo->method('existName')->with($command->getRol(), $command->getIdEmpresa())->willReturn(false);
        $roleRepo->method('update')->with($command)->willReturn($updatedRole);
        $roleService->method('showRoleInfoSimple')->with($updatedRole)->willReturn($roleInfo);

        // Caso de uso
        $useCase = new UpdateRoleUseCase($roleRepo, $companyRepo, $roleService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Role::class, $result);
        $this->assertEquals("Admin Info", $result->rol);
    }

    public function test_update_role_fails_when_invalid_company_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID empresa inválido");

        $command = new UpdateRoleCommand(
            id: 1,
            idEmpresa: 0, // ID inválido
            rol: "Admin",
            productos: true,
            categorias: true,
            tpv: true,
            usuarios: true,
            mesas: true,
            restaurante: true,
            clientes: true,
            empresa: true,
            pago: true
        );

        // Mocks
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $roleService = $this->createMock(RoleService::class);

        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(false);

        // Caso de uso
        $useCase = new UpdateRoleUseCase($roleRepo, $companyRepo, $roleService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }

    public function test_update_role_fails_when_invalid_role_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID rol inválido");

        $command = new UpdateRoleCommand(
            id: 0, // ID inválido
            idEmpresa: 1,
            rol: "Admin",
            productos: true,
            categorias: true,
            tpv: true,
            usuarios: true,
            mesas: true,
            restaurante: true,
            clientes: true,
            empresa: true,
            pago: true
        );

        // Mocks
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $roleService = $this->createMock(RoleService::class);

        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(true);
        $roleRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new UpdateRoleUseCase($roleRepo, $companyRepo, $roleService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
