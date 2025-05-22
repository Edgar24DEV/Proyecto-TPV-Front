<?php
// UpdateEmployeeHandler.php
namespace App\Application\Employee\Handlers;

use App\Application\Employee\DTO\AddEmployeeCommand;
use App\Application\Employee\DTO\DeleteEmployeeCommand;
use App\Application\Employee\DTO\FindByIdEmployeeCommand;
use App\Application\Employee\DTO\ListEmployeesCompanyCommand;
use App\Application\Employee\DTO\UpdateEmployeeCommand;
use App\Application\Employee\DTO\UpdateEmployeeDTO;
use App\Application\Employee\UseCases\DeleteEmployeeUseCase;
use App\Application\Employee\UseCases\FindByIdEmployeeUseCase;
use App\Application\Employee\UseCases\ListEmployeesCompanyUseCase;
use App\Domain\Employee\Entities\Employee;
use App\Domain\Audit\Services\AuditService;
use Illuminate\Support\Collection;

class ListEmployeesCompanyCommandHandler
{
    private ListEmployeesCompanyUseCase $listEmployeesCompanyUseCase;
    public function __construct(ListEmployeesCompanyUseCase $listEmployeesCompanyUseCase)
    {
        $this->listEmployeesCompanyUseCase = $listEmployeesCompanyUseCase;
    }

    public function handle(ListEmployeesCompanyCommand $command): Collection
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->listEmployeesCompanyUseCase->__invoke($command);
    }

}