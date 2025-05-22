<?php
// UpdateEmployeeHandler.php
namespace App\Application\Employee\Handlers;

use App\Application\Employee\DTO\AddEmployeeCommand;
use App\Application\Employee\DTO\DeleteEmployeeCommand;
use App\Application\Employee\DTO\FindByIdEmployeeCommand;
use App\Application\Employee\DTO\ListEmployeesRestaurantCommand;
use App\Application\Employee\DTO\UpdateEmployeeCommand;
use App\Application\Employee\DTO\UpdateEmployeeDTO;
use App\Application\Employee\UseCases\DeleteEmployeeUseCase;
use App\Application\Employee\UseCases\FindByIdEmployeeUseCase;
use App\Application\Employee\UseCases\ListEmployeesRestaurantUseCase;
use App\Domain\Employee\Entities\Employee;
use App\Domain\Audit\Services\AuditService;
use Illuminate\Support\Collection;

class ListEmployeesRestaurantCommandHandler
{
    private ListEmployeesRestaurantUseCase $listEmployeesRestaurantUseCase;
    public function __construct(ListEmployeesRestaurantUseCase $listEmployeesRestaurantUseCase)
    {
        $this->listEmployeesRestaurantUseCase = $listEmployeesRestaurantUseCase;
    }

    public function handle(ListEmployeesRestaurantCommand $command): Collection
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->listEmployeesRestaurantUseCase->__invoke($command);
    }

}