<?php

namespace App\Application\Employee\UseCases;

use App\Application\Employee\DTO\DeleteEmployeeCommand;
use App\Application\Employee\DTO\FindEmployeeCifCommand;
use App\Domain\Company\Entities\Employee;
use App\Domain\Company\Services\EmployeeService;
use App\Domain\Employee\Services\EmployeeService as ServicesEmployeeService;
use App\Infrastructure\Repositories\EloquentEmployeeRepository;

class DeleteEmployeeUseCase
{
    public function __construct(
        private readonly EloquentEmployeeRepository $EmployeeRepository,
        private readonly ServicesEmployeeService $EmployeeService
    ) {}

    public function __invoke(DeleteEmployeeCommand $command)   
    {
        $employee = $this->EmployeeRepository->findById($command->getId());

        if (!$employee) {
            throw new \Exception("Empleado no encontrado.");
        }

        return $this->EmployeeRepository->softDelete($command->getId());
    }
}
