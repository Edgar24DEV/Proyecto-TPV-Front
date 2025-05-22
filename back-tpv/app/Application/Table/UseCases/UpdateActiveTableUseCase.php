<?php

namespace App\Application\Table\UseCases;

use App\Application\Table\DTO\UpdateActiveTableCommand;
use App\Domain\Restaurant\Entities\Table;
use App\Domain\Restaurant\Services\TableService;
use App\Infrastructure\Repositories\EloquentLocationRepository;
use App\Infrastructure\Repositories\EloquentTableRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class UpdateActiveTableUseCase
{
    use ApiResponseTrait;

    public function __construct(
        private readonly EloquentTableRepository $tableRepository,
        private readonly EloquentLocationRepository $locationRepository,
        private readonly TableService $tableService,
    ) {}

    public function __invoke(UpdateActiveTableCommand $command): Table
    {
        $this->validateOrFail(
            $command->getId(),
            $command->getIdUbicacion(),
        );

        try {
            // Actualizar la mesa con las nuevas coordenadas
            $table = $this->tableRepository->updateActive($command);



            // Obtener información de la mesa después de la actualización
            $tableInfo = $this->tableService->showTableInfoSimple($table);
        } catch (\Exception $e) {
            Log::channel('restaurant')->error("Error al activar  mesa {$command->getMesa()} \n" .
                "   Clase: " . __CLASS__ . "\n" .
                "   Mensaje: " . $e->getMessage() . "\n" .
                "   Línea: " . $e->getLine() . "\n" .
                "   Trace:\n" . collect($e->getTrace())
                ->take(3)
                ->map(function ($trace, $i) {
                    return "    #$i " . ($trace['file'] ?? '') . ':' . ($trace['line'] ?? '') . ' → ' . ($trace['function'] ?? '');
                })
                ->implode("\n") . "\n");
            // En caso de error, devolver una mesa vacía como valor de fallback
            $tableVacio = new Table(
                id: -1,
                mesa: "",
                activo: false,
                idUbicacion: -1,
                posX: 0,  // Posición por defecto
                posY: 0   // Posición por defecto
            );
            return $tableVacio;
        }

        return $tableInfo;
    }

    private function validateOrFail(int $idMesa, int $idUbicacion): void
    {
        if ($idMesa <= 0 || !$this->tableRepository->exist($idMesa)) {
            throw new \Exception("ID mesa inválido");
        }
        if ($idUbicacion <= 0 || !$this->locationRepository->exist($idUbicacion)) {
            throw new \Exception("ID ubicación inválido");
        }
    }
}
