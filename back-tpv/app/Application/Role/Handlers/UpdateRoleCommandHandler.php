<?php
namespace App\Application\Role\Handlers;

use App\Application\Role\DTO\UpdateRoleCommand;
use App\Application\Role\UseCases\UpdateRoleUseCase;
use App\Domain\Employee\Entities\Role;

class UpdateRoleCommandHandler
{
    private UpdateRoleUseCase $updateRoleUseCase;


    public function __construct(UpdateRoleUseCase $updateRoleUseCase)
    {
        $this->updateRoleUseCase = $updateRoleUseCase;
    }

    public function handle(UpdateRoleCommand $command): Role
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->updateRoleUseCase->__invoke($command);
    }

}
