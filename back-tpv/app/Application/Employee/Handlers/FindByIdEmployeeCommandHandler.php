<?php
// UpdateEmployeeHandler.php
namespace App\Application\Employee\Handlers;

use App\Application\Employee\DTO\AddEmployeeCommand;
use App\Application\Employee\DTO\DeleteEmployeeCommand;
use App\Application\Employee\DTO\FindByIdEmployeeCommand;
use App\Application\Employee\DTO\UpdateEmployeeCommand;
use App\Application\Employee\DTO\UpdateEmployeeDTO;
use App\Application\Employee\UseCases\DeleteEmployeeUseCase;
use App\Application\Employee\UseCases\FindByIdEmployeeUseCase;
use App\Domain\Employee\Entities\Employee;
use App\Domain\Audit\Services\AuditService;

class FindByIdEmployeeCommandHandler
{
    private FindByIdEmployeeUseCase $findByIdEmployeeUseCase;
    public function __construct(FindByIdEmployeeUseCase $findByIdEmployeeUseCase)
    {
        $this->findByIdEmployeeUseCase = $findByIdEmployeeUseCase;
    }

    public function handle(FindByIdEmployeeCommand $command): Employee
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->findByIdEmployeeUseCase->__invoke($command);
    }

}