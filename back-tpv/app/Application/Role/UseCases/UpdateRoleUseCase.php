<?php

namespace App\Application\Role\UseCases;

use App\Application\Role\DTO\UpdateRoleCommand;
use App\Domain\Employee\Entities\Role;
use App\Domain\Employee\Services\RoleService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;

class UpdateRoleUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentRoleRepository $roleRepository,
        private readonly EloquentCompanyRepository $companyRepository,
        private readonly RoleService $roleService,
    ) {
    }
    public function __invoke(UpdateRoleCommand $command): Role
    {

        $this->validateOrFail(
            $command->getIdEmpresa(),
            $command->getId(),
            $command->getRol()
        );

        try {
            $role = $this->roleRepository->update($command);
            $roleInfo = $this->roleService->showRoleInfoSimple($role);
        } catch (\Exception $e) {
            return new Role(
                id: -1,
                rol: '',
                productos: false,
                categorias: false,
                tpv: false,
                usuarios: false,
                mesas: false,
                restaurante: false,
                clientes: false,
                empresa: false,
                pago: false,
                idEmpresa: null
            );
        }
        return $roleInfo;
    }


    private function validateOrFail($idEmpresa, $idRole, string $rol): void
    {
        if ($idEmpresa <= 0 || !$this->companyRepository->exist($idEmpresa)) {
            throw new \InvalidArgumentException("ID empresa inválido");
        }
        if ($idRole <= 0 || !$this->roleRepository->exist($idRole)) {
            throw new \InvalidArgumentException("ID rol inválido");
        }
        if ($idRole <= 0 || !$this->roleRepository->existwithCompany($idRole,$idEmpresa)) {
            throw new \InvalidArgumentException("El role no le correspondie a esa empresa");
        }
    }
}
