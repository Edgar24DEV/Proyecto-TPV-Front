<?php
// UpdateEmployeeRoleHandler.php
namespace App\Application\Employee\Handlers;

use App\Application\Employee\DTO\UpdateEmployeeRoleCommand;
use App\Application\Employee\DTO\UpdateEmployeeRoleDTO;
use App\Application\Employee\UseCases\UpdateEmployeeRoleUseCase;
use App\Domain\Employee\Entities\Employee;
use App\Domain\Audit\Services\AuditService;

class UpdateEmployeeRoleCommandHandler
{
    private UpdateEmployeeRoleUseCase $updateEmployeeRoleUseCase;


    public function __construct(UpdateEmployeeRoleUseCase $updateEmployeeRoleUseCase)
    {
        $this->updateEmployeeRoleUseCase = $updateEmployeeRoleUseCase;
    }

    public function handle(UpdateEmployeeRoleCommand $command): Employee
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->updateEmployeeRoleUseCase->__invoke($command);
    }

    private function isEmployeeAllowedToUpdate(int $employeeId): bool
    {
        // Lógica ficticia para verificar si el empleado tiene permisos para actualizar
        return true;
    }
}