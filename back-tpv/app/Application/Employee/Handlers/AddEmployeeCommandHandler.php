<?php
// UpdateEmployeeHandler.php
namespace App\Application\Employee\Handlers;

use App\Application\Employee\DTO\AddEmployeeCommand;
use App\Application\Employee\DTO\UpdateEmployeeCommand;
use App\Application\Employee\DTO\UpdateEmployeeDTO;
use App\Application\Employee\UseCases\AddEmployeeUseCase;
use App\Domain\Employee\Entities\Employee;
use App\Domain\Audit\Services\AuditService;

class AddEmployeeCommandHandler
{
    private AddEmployeeUseCase $addEmployeeUseCase;


    public function __construct(AddEmployeeUseCase $addEmployeeUseCase)
    {
        $this->addEmployeeUseCase = $addEmployeeUseCase;
    }

    public function handle(AddEmployeeCommand $command): Employee
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->addEmployeeUseCase->__invoke($command);
    }

    private function isEmployeeAllowedToUpdate(int $employeeId): bool
    {
        // Lógica ficticia para verificar si el empleado tiene permisos para actualizar
        return true;
    }
}