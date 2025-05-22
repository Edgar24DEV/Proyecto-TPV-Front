<?php

namespace App\Application\Role\UseCases;

use App\Application\Role\DTO\DeleteRoleCommand;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;

class DeleteRoleUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentRoleRepository $roleRepository,
    ) {
    }
    public function __invoke(DeleteRoleCommand $command): bool
    {
        $this->validateOrFail($command->getId());
        try {
            $respuesta = $this->roleRepository->delete($command->getId());
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