<?php

namespace App\Application\Table\UseCases;

use App\Application\Table\DTO\UpdateTableCommand;
use App\Domain\Restaurant\Entities\Table;
use App\Domain\Restaurant\Services\TableService;
use App\Infrastructure\Repositories\EloquentLocationRepository; // Corregido el repositorio
use App\Infrastructure\Repositories\EloquentTableRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class UpdateTableUseCase
{
    use ApiResponseTrait;

    public function __construct(
        private readonly EloquentTableRepository $tableRepository,
        private readonly EloquentLocationRepository $locationRepository, // Repositorio de ubicaciones
        private readonly TableService $tableService,
    ) {}

    public function __invoke(UpdateTableCommand $command): Table
    {
        $this->validateOrFail(
            $command->getId(),
            $command->getIdUbicacion(),
        );

        try {
            $table = $this->tableRepository->update($command);
            $tableInfo = $this->tableService->showTableInfoSimple($table);
        } catch (\Exception $e) {
            Log::channel('restaurant')->error("Error al añadir mesa {$command->getMesa()} \n" .
                "   Clase: " . __CLASS__ . "\n" .
                "   Mensaje: " . $e->getMessage() . "\n" .
                "   Línea: " . $e->getLine() . "\n" .
                "   Trace:\n" . collect($e->getTrace())
                ->take(3)
                ->map(function ($trace, $i) {
                    return "    #$i " . ($trace['file'] ?? '') . ':' . ($trace['line'] ?? '') . ' → ' . ($trace['function'] ?? '');
                })
                ->implode("\n") . "\n");
            // Lanzar una excepción o devolver una respuesta de error
            throw new \Exception("Error al actualizar la mesa: " . $e->getMessage());
        }

        return $tableInfo;
    }

    private function validateOrFail(int $idMesa, int $idUbicacion): void
    {
        if ($idMesa <= 0 || !$this->tableRepository->exist($idMesa)) {
            throw new \Exception("ID mesa inválido");
        }

        // Corregido el repositorio de ubicación
        if ($idUbicacion <= 0 || !$this->locationRepository->exist($idUbicacion)) {
            throw new \Exception("ID ubicación inválido");
        }
    }
}
