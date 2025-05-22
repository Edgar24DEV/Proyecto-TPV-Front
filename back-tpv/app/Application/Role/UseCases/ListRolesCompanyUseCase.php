<?php
namespace App\Application\Role\UseCases;
use App\Application\Role\DTO\ListRolesCompanyCommand;
use App\Domain\Employee\Services\RoleService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use Illuminate\Support\Collection;
use function PHPUnit\Framework\isNan;

final class ListRolesCompanyUseCase
{



    public function __construct(
        private readonly EloquentRoleRepository $roleRepository,
        private readonly EloquentCompanyRepository $companyRepository,
        private readonly RoleService $roleService,
    ) {
    }
    public function __invoke(ListRolesCompanyCommand $command): Collection
    {
        $idEmpresa = $command->getIdEmpresa();
        $this->validateOrFail($idEmpresa);
        $roles = $this->roleRepository->findByCompanyID($idEmpresa);
        $roles = $this->roleService->showRoleInfo($roles);
        return $roles;
    }


    private function validateOrFail(int $idEmpresa): void
    {
        if ($idEmpresa <= 0 || !$this->companyRepository->exist($idEmpresa)) {
            throw new \InvalidArgumentException("ID inválido o no existe");
        }
    }

}