<?php

namespace App\Application\Role\UseCases;

use App\Application\Role\DTO\OneRoleCommand;
use App\Domain\Employee\Entities\Role;
use App\Domain\Employee\Services\RoleService;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;

class OneRoleUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentRoleRepository $roleRepository,
        private readonly RoleService $roleService,
    ) {
    }
    public function __invoke(OneRoleCommand $command): Role
    {
        $this->validateOrFail($command->getId());
        try {
            $respuesta = $this->roleRepository->findById($command);
            $respuesta = $this->roleService->showRoleInfoSimple($respuesta);
        } catch (\Exception $e) {
            return $respuesta;
        }
        return $respuesta;
    }

    private function validateOrFail(int $id): void
    {
        if ($id <= 0 || !$this->roleRepository->exist($id)) {
            throw new \InvalidArgumentException("ID rol inválido");
        }
    }
}