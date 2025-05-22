<?php
namespace App\Application\Employee\UseCases;
use App\Application\Employee\DTO\ListEmployeeRestaurantsCommand;
use App\Domain\Restaurant\Services\RestaurantService;
use App\Infrastructure\Repositories\EloquentEmployeeRepository;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Domain\Employee\Services\EmployeeService;
use Illuminate\Support\Collection;
use function PHPUnit\Framework\isNan;

final class ListEmployeeRestaurantsUseCase
{



    public function __construct(
        private readonly EloquentEmployeeRepository $employeeRepository,
        private readonly EloquentCompanyRepository $companyRepository,
        private readonly RestaurantService $restaurantService
    ) {
    }
    public function __invoke(ListEmployeeRestaurantsCommand $command): Collection
    {
        $idCompany = $command->getIdEmpresa();
        $idEmployee = $command->getIdEmpleado();
        $this->validateOrFail($idCompany, $idEmployee);
        $employees = $this->employeeRepository->findEmployeeRestaurantsByCompany($idCompany, $idEmployee);
        $employees = $this->restaurantService->showRestaurantInfo($employees);
        return $employees;
    }


    private function validateOrFail(int $idCompany, $idEmployee): void
    {
        if ($idCompany <= 0 || $idEmployee <= 0) {
            throw new \InvalidArgumentException("ID inválido");
        }

        if (!$this->companyRepository->exist($idCompany)) {
            throw new \InvalidArgumentException("ID de la compañia no existe");
        }
        if (!$this->employeeRepository->exist($idEmployee)) {
            throw new \InvalidArgumentException("ID del empleado no existe");
        }

    }

}