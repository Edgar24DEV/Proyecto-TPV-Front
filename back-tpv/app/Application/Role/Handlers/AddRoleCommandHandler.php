<?php

namespace App\Application\Role\Handlers;

use App\Application\Role\DTO\AddRoleCommand;
use App\Application\Role\UseCases\AddRoleUseCase;
use App\Domain\Employee\Entities\Role;

class AddRoleCommandHandler
{
    public function __construct(
        private readonly AddRoleUseCase $addRoleUseCase
    ) {}

    public function handle(AddRoleCommand $command): Role
    {
        return $this->addRoleUseCase->__invoke($command);
    }
}
