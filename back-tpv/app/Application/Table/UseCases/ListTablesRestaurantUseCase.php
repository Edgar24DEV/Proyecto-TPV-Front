<?php
namespace App\Application\Table\UseCases;
use App\Application\Table\DTO\ListTablesRestaurantCommand;
use App\Domain\Restaurant\Services\TableService;
use App\Infrastructure\Repositories\EloquentTableRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use Illuminate\Support\Collection;
use function PHPUnit\Framework\isNan;

final class ListTablesRestaurantUseCase
{



    public function __construct(
        private readonly EloquentTableRepository $tableRepository,
        private readonly EloquentRestaurantRepository $restaurantRepository,
        private readonly TableService $tableService,
    ) {
    }
    public function __invoke(ListTablesRestaurantCommand $command): Collection
    {
        $idRestaurant = $command->getIdRestaurant();
        $this->validateOrFail($idRestaurant);
        $tables = $this->tableRepository->find($idRestaurant);
        $tables = $this->tableService->showTableInfo($tables);
        return $tables;
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