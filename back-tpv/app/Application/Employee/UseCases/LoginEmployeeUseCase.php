<?php

namespace App\Application\Employee\UseCases;

use App\Application\Employee\DTO\LoginEmployeeCommand;
use App\Domain\Employee\Entities\Employee;
use App\Domain\Employee\Services\EmployeeService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentEmployeeRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

final class LoginEmployeeUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentEmployeeRepository $employeeRepository,
        private readonly EmployeeService $employeeService,
    ) {
    }
    public function __invoke(LoginEmployeeCommand $command): Employee
    {

        $this->validateOrFail($command->getId());
        try {
            $respuesta = $this->employeeRepository->login($command);
            $employeeInfo = $this->employeeService->showEmployeeInfoSimple($respuesta);
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

    private function validateOrFail(int $id): void
    {
        if ($id <= 0 || !$this->employeeRepository->exist($id)) {
            throw new \Exception("ID empleado inválido");
        }
    }
}
