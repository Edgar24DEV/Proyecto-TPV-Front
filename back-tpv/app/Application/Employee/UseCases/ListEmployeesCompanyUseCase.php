<?php
namespace App\Application\Employee\UseCases;
use App\Application\Employee\DTO\ListEmployeesCompanyCommand;
use App\Infrastructure\Repositories\EloquentEmployeeRepository;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Domain\Employee\Services\EmployeeService;
use Illuminate\Support\Collection;
use function PHPUnit\Framework\isNan;

final class ListEmployeesCompanyUseCase
{



    public function __construct(
        private readonly EloquentEmployeeRepository $employeeRepository,
        private readonly EloquentCompanyRepository $companyRepository,
        private readonly EmployeeService $employeeService
    ) {
    }
    public function __invoke(ListEmployeesCompanyCommand $command): Collection
    {
        $idCompany = $command->getIdEmpresa();
        $this->validateOrFail($idCompany);
        $employees = $this->employeeRepository->findByCompany($idCompany);
        $employees = $this->employeeService->showEmployeeInfo($employees);
        return $employees;
    }


    private function validateOrFail(int $idCompany): void
    {
        if ($idCompany <= 0) {
            throw new \InvalidArgumentException("ID inválido");
        }

        if (!$this->companyRepository->exist($idCompany)) {
            throw new \InvalidArgumentException("ID No existe");
        }

    }

}