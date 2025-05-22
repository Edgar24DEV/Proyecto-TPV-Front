<?php

namespace App\Application\Table\UseCases;

use App\Application\Table\DTO\AddTableCommand;
use App\Domain\Restaurant\Entities\Table;
use App\Domain\Restaurant\Services\TableService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentLocationRepository;
use App\Infrastructure\Repositories\EloquentTableRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class AddTableUseCase
{
    use ApiResponseTrait;

    public function __construct(
        private readonly EloquentTableRepository $tableRepository,
        private readonly EloquentRestaurantRepository $restaurantRepository,
        private readonly EloquentRoleRepository $rolRepository,
        private readonly EloquentLocationRepository $locationRepository,
        private readonly TableService $tableService,
    ) {}

    public function __invoke(AddTableCommand $command): Table
    {
        $this->validateOrFail(
            $command->getIdUbicacion(),
            $command->getMesa()
        );

        try {
            $table = $this->tableRepository->create($command);
            $tableInfo = $this->tableService->showTableInfoSimple($table);
        } catch (\Exception $e) {
            Log::channel('restaurant')->error("Error al añadir mesa} \n" .
                "   Clase: " . __CLASS__ . "\n" .
                "   Mensaje: " . $e->getMessage() . "\n" .
                "   Línea: " . $e->getLine() . "\n" .
                "   Trace:\n" . collect($e->getTrace())
                ->take(3)
                ->map(function ($trace, $i) {
                    return "    #$i " . ($trace['file'] ?? '') . ':' . ($trace['line'] ?? '') . ' → ' . ($trace['function'] ?? '');
                })
                ->implode("\n") . "\n");
            $tableVacio = new Table(
                id: -1,
                mesa: "",
                activo: false,
                idUbicacion: -1,
                posX: $command->getPosX() ?? 0,
                posY: $command->getPosY() ?? 0,
            );
            return $tableVacio;
        }

        return $tableInfo;
    }

    private function validateOrFail($idUbicacion, $mesa): void
    {
        if ($idUbicacion <= 0 || !$this->locationRepository->exist($idUbicacion)) {
            throw new \Exception("ID ubicación inválido");
        }

        if ($this->tableRepository->existTable($idUbicacion, $mesa) || !$mesa) {
            throw new \Exception("Ya existe la mesa");
        }
    }
}
