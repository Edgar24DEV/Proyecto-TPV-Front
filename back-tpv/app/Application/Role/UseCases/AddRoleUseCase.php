<?php

namespace App\Application\Role\UseCases;

use App\Application\Role\DTO\AddRoleCommand;
use App\Domain\Employee\Entities\Role;
use App\Domain\Employee\Services\RoleService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;

class AddRoleUseCase
{
    use ApiResponseTrait;

    public function __construct(
        private readonly EloquentRoleRepository $rolRepository,
        private readonly EloquentCompanyRepository $companyRepository,
        private readonly RoleService $roleService,
    ) {}

    public function __invoke(AddRoleCommand $command): Role
    {
        $this->validateOrFail(
            $command->getIdEmpresa(),
            $command->getRol()
        );

        try {
            $role = $this->rolRepository->create($command);
            return $this->roleService->showRoleInfoSimple($role);

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
    }

    private function validateOrFail(?int $idEmpresa, string $rol): void
    {
        if ($idEmpresa === null || $idEmpresa <= 0 || !$this->companyRepository->exist($idEmpresa)) {
            throw new \InvalidArgumentException("ID empresa inválido");
        }

        if ($this->rolRepository->existName($rol, $idEmpresa)) {
            throw new \InvalidArgumentException("El rol ya existe");
        }
    }
}
