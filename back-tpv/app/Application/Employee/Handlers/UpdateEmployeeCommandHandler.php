<?php
// UpdateEmployeeHandler.php
namespace App\Application\Employee\Handlers;

use App\Application\Employee\DTO\UpdateEmployeeCommand;
use App\Application\Employee\DTO\UpdateEmployeeDTO;
use App\Application\Employee\UseCases\UpdateEmployeeUseCase;
use App\Domain\Employee\Entities\Employee;
use App\Domain\Audit\Services\AuditService;

class UpdateEmployeeCommandHandler
{
    private UpdateEmployeeUseCase $updateEmployeeUseCase;


    public function __construct(UpdateEmployeeUseCase $updateEmployeeUseCase)
    {
        $this->updateEmployeeUseCase = $updateEmployeeUseCase;
    }

    public function handle(UpdateEmployeeCommand $command): Employee
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->updateEmployeeUseCase->__invoke($command);
    }

    private function isEmployeeAllowedToUpdate(int $employeeId): bool
    {
        // Lógica ficticia para verificar si el empleado tiene permisos para actualizar
        return true;
    }
}