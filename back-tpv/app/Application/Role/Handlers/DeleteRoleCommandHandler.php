<?php
// UpdateRoleHandler.php
namespace App\Application\Role\Handlers;

use App\Application\Role\DTO\DeleteRoleCommand;
use App\Application\Role\UseCases\DeleteRoleUseCase;
use App\Domain\Employee\Entities\Role;
use App\Domain\Audit\Services\AuditService;

class DeleteRoleCommandHandler
{
    private DeleteRoleUseCase $deleteRoleUseCase;


    public function __construct(DeleteRoleUseCase $deleteRoleUseCase)
    {
        $this->deleteRoleUseCase = $deleteRoleUseCase;
    }

    public function handle(DeleteRoleCommand $command): bool
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->deleteRoleUseCase->__invoke($command);
    }

}