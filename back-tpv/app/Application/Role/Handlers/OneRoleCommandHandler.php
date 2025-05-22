<?php
// UpdateRoleHandler.php
namespace App\Application\Role\Handlers;

use App\Application\Role\DTO\OneRoleCommand;
use App\Application\Role\UseCases\OneRoleUseCase;
use App\Domain\Employee\Entities\Role;
use App\Domain\Audit\Services\AuditService;

class OneRoleCommandHandler
{
    private OneRoleUseCase $oneRoleUseCase;


    public function __construct(OneRoleUseCase $oneRoleUseCase)
    {
        $this->oneRoleUseCase = $oneRoleUseCase;
    }

    public function handle(OneRoleCommand $command): Role
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->oneRoleUseCase->__invoke($command);
    }

}