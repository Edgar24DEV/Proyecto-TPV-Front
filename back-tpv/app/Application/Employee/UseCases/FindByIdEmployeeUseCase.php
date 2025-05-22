<?php
namespace App\Application\Employee\UseCases;
use App\Application\Employee\DTO\FindByIdEmployeeCommand;
use App\Domain\Employee\Entities\Employee;
use App\Infrastructure\Repositories\EloquentEmployeeRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Domain\Employee\Services\EmployeeService;
use function PHPUnit\Framework\isNan;

final class FindByIdEmployeeUseCase
{



    public function __construct(
        private readonly EloquentEmployeeRepository $employeeRepository,
        private readonly EloquentRestaurantRepository $restaurantRepository,
        private readonly EmployeeService $employeeService
    ) {
    }
    public function __invoke(FindByIdEmployeeCommand $command): Employee
    {
        $this->validateOrFail($command->getId());
        $employee = $this->employeeRepository->findById($command->getId());
        $employeeInfo = $this->employeeService->showEmployeeInfoSimple($employee);
        return $employeeInfo;
    }


    private function validateOrFail(int $id): void
    {
        if ($id <= 0 || !$this->employeeRepository->exist($id)) {
            throw new \Exception("ID empleado inválido");
        }
    }
}