<?php
// UpdateEmployeeHandler.php
namespace App\Application\Employee\Handlers;

use App\Application\Employee\DTO\AddEmployeeCommand;
use App\Application\Employee\DTO\DeleteEmployeeCommand;
use App\Application\Employee\DTO\UpdateEmployeeCommand;
use App\Application\Employee\DTO\UpdateEmployeeDTO;
use App\Application\Employee\UseCases\DeleteEmployeeUseCase;
use App\Domain\Employee\Entities\Employee;
use App\Domain\Audit\Services\AuditService;

class DeleteEmployeeCommandHandler
{
    private DeleteEmployeeUseCase $deleteEmployeeUseCase;


    public function __construct(DeleteEmployeeUseCase $deleteEmployeeUseCase)
    {
        $this->deleteEmployeeUseCase = $deleteEmployeeUseCase;
    }

    public function handle(DeleteEmployeeCommand $command): bool
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->deleteEmployeeUseCase->__invoke($command);
    }

}