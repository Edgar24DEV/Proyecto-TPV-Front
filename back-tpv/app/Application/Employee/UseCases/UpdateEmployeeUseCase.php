<?php

namespace App\Application\Employee\UseCases;

use App\Application\Employee\DTO\UpdateEmployeeCommand;
use App\Domain\Employee\Entities\Employee;
use App\Domain\Employee\Services\EmployeeService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentEmployeeRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class UpdateEmployeeUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentEmployeeRepository $employeeRepository,
        private readonly EloquentRestaurantRepository $restaurantRepository,
        private readonly EloquentRoleRepository $rolRepository,
        private readonly EloquentCompanyRepository $companyRepository,
        private readonly EmployeeService $employeeService,
    ) {
    }
    public function __invoke(UpdateEmployeeCommand $command): Employee
    {
        $this->validateOrFail(
            $command->getIdRol(),
            $command->getIdEmpresa(),
            $command->getId()
        );

        // $employee = $this->employeeService->requestEmployee($command);
        try {
            $employee = $this->employeeRepository->update($command);
            $employeeInfo = $this->employeeService->showEmployeeInfoSimple($employee);
        } catch (\Exception $e) {

            $employeeVacio = new Employee(
                id: -1,
                nombre: "",
                pin: null,
                idRol: -1,
                idEmpresa: null,
            );
            return $employeeVacio;
        }
        return $employeeInfo;
    }


    private function validateOrFail(int $idRol, int $idEmpresa, int $idEmpleado): void
    {
        if ($idEmpleado <= 0 || !$this->employeeRepository->exist($idEmpleado)) {
            throw new \Exception("ID empleado inválido");
        }
        if ($idEmpresa <= 0 || !$this->companyRepository->exist($idEmpresa)) {
            throw new \Exception("ID empresa inválido");
        }
        if ($idRol <= 0 || !$this->rolRepository->exist($idRol)) {
            throw new \Exception("ID rol inválido");
        }
    }
}
