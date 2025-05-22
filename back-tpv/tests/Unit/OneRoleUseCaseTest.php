<?php

namespace Tests\Unit\Application\Role\UseCases;

use App\Application\Role\DTO\OneRoleCommand;
use App\Application\Role\UseCases\OneRoleUseCase;
use App\Domain\Employee\Entities\Role;
use App\Domain\Employee\Services\RoleService;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use PHPUnit\Framework\TestCase;

class OneRoleUseCaseTest extends TestCase
{
    public function test_find_role_successfully()
    {
        // Arrange
        $command = new OneRoleCommand(id: 1);

        // Mocks
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $roleService = $this->createMock(RoleService::class);

        // Mock role data
        $foundRole = new Role(
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
        $roleRepo->method('exist')->with($command->getId())->willReturn(true);
        $roleRepo->method('findById')->with($command)->willReturn($foundRole);
        $roleService->method('showRoleInfoSimple')->with($foundRole)->willReturn($roleInfo);

        // Caso de uso
        $useCase = new OneRoleUseCase($roleRepo, $roleService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Role::class, $result);
        $this->assertEquals("Admin Info", $result->rol);
    }

    public function test_find_role_fails_when_invalid_role_id()
    {
        // Arrange
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ID rol inválido");

        $command = new OneRoleCommand(id: 0); // ID inválido

        // Mocks
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $roleService = $this->createMock(RoleService::class);

        $roleRepo->method('exist')->with($command->getId())->willReturn(false);

        // Caso de uso
        $useCase = new OneRoleUseCase($roleRepo, $roleService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
