<?php
namespace App\Application\Employee\UseCases;
use App\Application\Employee\DTO\ListEmployeesRestaurantCommand;
use App\Infrastructure\Repositories\EloquentEmployeeRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Domain\Employee\Services\EmployeeService;
use Illuminate\Support\Collection;
use function PHPUnit\Framework\isNan;

final class ListEmployeesRestaurantUseCase
{



    public function __construct(
        private readonly EloquentEmployeeRepository $employeeRepository,
        private readonly EloquentRestaurantRepository $restaurantRepository,
        private readonly EmployeeService $employeeService
    ) {
    }
    public function __invoke(ListEmployeesRestaurantCommand $command): Collection
    {
        $idRestaurant = $command->getIdRestaurant();
        $this->validateOrFail($idRestaurant);
        $employees = $this->employeeRepository->find($idRestaurant);
        $employees = $this->employeeService->showEmployeeInfo($employees);
        return $employees;
    }


    private function validateOrFail(int $idRestaurant): void
    {
        if ($idRestaurant <= 0) {
            throw new \InvalidArgumentException("ID inválido");
        }

        if (!$this->restaurantRepository->exist($idRestaurant)) {
            throw new \InvalidArgumentException("ID No existe");
        }

    }

}