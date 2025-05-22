<?php
// UpdateEmployeeHandler.php
namespace App\Application\Employee\Handlers;

use App\Application\Employee\DTO\AddEmployeeCommand;
use App\Application\Employee\DTO\DeleteEmployeeCommand;
use App\Application\Employee\DTO\FindByIdEmployeeCommand;
use App\Application\Employee\DTO\LoginEmployeeCommand;
use App\Application\Employee\DTO\UpdateEmployeeCommand;
use App\Application\Employee\DTO\UpdateEmployeeDTO;
use App\Application\Employee\UseCases\DeleteEmployeeUseCase;
use App\Application\Employee\UseCases\FindByIdEmployeeUseCase;
use App\Application\Employee\UseCases\LoginEmployeeUseCase;
use App\Domain\Employee\Entities\Employee;
use App\Domain\Audit\Services\AuditService;

class LoginEmployeeCommandHandler
{
    private LoginEmployeeUseCase $loginEmployeeUseCase;
    public function __construct(LoginEmployeeUseCase $loginEmployeeUseCase)
    {
        $this->loginEmployeeUseCase = $loginEmployeeUseCase;
    }

    public function handle(LoginEmployeeCommand $command): Employee
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->loginEmployeeUseCase->__invoke($command);
    }

}