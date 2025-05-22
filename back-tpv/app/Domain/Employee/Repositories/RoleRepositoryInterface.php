<?php

namespace App\Domain\Employee\Repositories;

use App\Application\Role\DTO\AddRoleCommand;
use App\Application\Role\DTO\UpdateRoleCommand;
use App\Domain\Employee\Entities\Role;

interface RoleRepositoryInterface
{
    public function exist(int $idRol): bool;
    public function existName(string $name, int $idEmpresa): bool;
    public function existwithCompany(int $idRol, int $idEmpresa): bool;
    public function findByCompanyId(int $companyId): array;
    public function create(AddRoleCommand $command): Role;
    public function update(UpdateRoleCommand $command): Role;
    public function delete(int $id): bool;
}