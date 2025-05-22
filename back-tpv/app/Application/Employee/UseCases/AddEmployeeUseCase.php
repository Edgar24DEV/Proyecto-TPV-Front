<?php

namespace App\Application\Employee\UseCases;

use App\Application\Employee\DTO\AddEmployeeCommand;
use App\Domain\Employee\Entities\Employee;
use App\Domain\Employee\Services\EmployeeService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentEmployeeRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class AddEmployeeUseCase
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
    public function __invoke(AddEmployeeCommand $command): Employee
    {

        $this->validateOrFail(
            $command->getIdRestaurante(),
            $command->getIdRol(),
            $command->getIdEmpresa()
        );

        try {
            $employee = $this->employeeRepository->create($command);
            $employeeInfo = $this->employeeService->showEmployeeInfoSimple($employee);
        } catch (\Exception $e) {
            Log::channel('employee')->error("Error añadiendo empleado}\n" .
            "   Clase: " . __CLASS__ . "\n" .
            "   Mensaje: " . $e->getMessage() . "\n" .
            "   Línea: " . $e->getLine() . "\n" .
            "   Trace:\n" . collect($e->getTrace())
            ->take(3)
            ->map(function ($trace, $i) {
                return "    #$i " . ($trace['file'] ?? '') . ':' . ($trace['line'] ?? '') . ' → ' . ($trace['function'] ?? '');
            })
            ->implode("\n") . "\n");

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


    private function validateOrFail(int $idRestaurant, int $idRol, int $idEmpresa): void
    {
        if ($idRestaurant <= 0 || !$this->restaurantRepository->exist($idRestaurant)) {
            throw new \Exception("ID restaurante inválido");
        }
        if ($idEmpresa <= 0 || !$this->companyRepository->exist($idEmpresa)) {
            throw new \Exception("ID empresa inválido");
        }
        if (!$this->restaurantRepository->existwithCompany($idRestaurant, $idEmpresa)) {
            throw new \Exception("El restaurante no es de esa empresa");
        }
        if ($idRol <= 0 || !$this->rolRepository->exist($idRol)) {
            throw new \Exception("ID rol inválido");
        }
    }
}