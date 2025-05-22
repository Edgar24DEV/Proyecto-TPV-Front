<?php

namespace Tests\Unit\Application\Role\UseCases;

use App\Application\Role\DTO\ListRolesCompanyCommand;
use App\Application\Role\UseCases\ListRolesCompanyUseCase;
use App\Domain\Employee\Entities\Role;
use App\Domain\Employee\Services\RoleService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class ListRolesCompanyUseCaseTest extends TestCase
{
    public function test_list_roles_successfully()
    {
        // Arrange
        $command = new ListRolesCompanyCommand(idEmpresa: 1);

        // Mocks
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $roleService = $this->createMock(RoleService::class);

        // Mock role data
        $roles = [
            new Role(id: 1, rol: "Admin", productos: true, categorias: true, tpv: true, usuarios: true, mesas: true, restaurante: true, clientes: true, empresa: true, pago: true, idEmpresa: 1),
            new Role(id: 2, rol: "Empleado", productos: false, categorias: false, tpv: true, usuarios: false, mesas: true, restaurante: false, clientes: true, empresa: false, pago: false, idEmpresa: 1)
        ];

        $processedRoles = new Collection([
            new Role(id: 1, rol: "Admin Info", productos: true, categorias: true, tpv: true, usuarios: true, mesas: true, restaurante: true, clientes: true, empresa: true, pago: true, idEmpresa: 1),
            new Role(id: 2, rol: "Empleado Info", productos: false, categorias: false, tpv: true, usuarios: false, mesas: true, restaurante: false, clientes: true, empresa: false, pago: false, idEmpresa: 1)
        ]);

        // Expectations
        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(true);
        $roleRepo->method('findByCompanyId')->with($command->getIdEmpresa())->willReturn($roles);  // Devuelve un array, no una Collection
        $roleService->method('showRoleInfo')->with($roles)->willReturn($processedRoles);

        // Caso de uso
        $useCase = new ListRolesCompanyUseCase($roleRepo, $companyRepo, $roleService);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertEquals("Admin Info", $result[0]->rol);
        $this->assertEquals("Empleado Info", $result[1]->rol);
    }


    public function test_list_roles_fails_when_invalid_company_id()
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ID inválido o no existe");

        $command = new ListRolesCompanyCommand(idEmpresa: 0); // ID inválido

        // Mocks
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $companyRepo = $this->createMock(EloquentCompanyRepository::class);
        $roleService = $this->createMock(RoleService::class);

        $companyRepo->method('exist')->with($command->getIdEmpresa())->willReturn(false);

        // Caso de uso
        $useCase = new ListRolesCompanyUseCase($roleRepo, $companyRepo, $roleService);

        // Act & Assert (se espera excepción)
        $useCase($command);
    }
}
