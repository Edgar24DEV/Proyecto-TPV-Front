<?php
namespace App\Application\Table\UseCases;
use App\Application\Table\DTO\FindByIdTableCommand;
use App\Domain\Restaurant\Entities\Table;
use App\Domain\Restaurant\Services\TableService;
use App\Infrastructure\Repositories\EloquentTableRepository;
use function PHPUnit\Framework\isNan;

final class FindByIdTableUseCase
{



    public function __construct(
        private readonly EloquentTableRepository $tableRepository,
        private readonly TableService $tableService
    ) {
    }
    public function __invoke(FindByIdTableCommand $command): Table
    {
        $this->validateOrFail($command->getId());
        $table = $this->tableRepository->findById($command->getId());
        $tableInfo = $this->tableService->showTableInfoSimple($table);
        return $tableInfo;
    }


    private function validateOrFail(int $id): void
    {
        if ($id <= 0 || !$this->tableRepository->exist($id)) {
            throw new \Exception("ID mesa inválido");
        }
    }
}