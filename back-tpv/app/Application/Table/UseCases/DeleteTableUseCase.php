<?php

namespace App\Application\Table\UseCases;

use App\Application\Order\DTO\GetOrderCommand;
use App\Application\Table\DTO\DeleteTableCommand;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use App\Infrastructure\Repositories\EloquentTableRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class DeleteTableUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentTableRepository $tableRepository,
        private readonly EloquentOrderRepository $orderRepository,
    ) {}
    public function __invoke(DeleteTableCommand $command): bool
    {

        $this->validateOrFail($command->getId());
        try {
            $respuesta = $this->tableRepository->delete($command->getId());
        } catch (\Exception $e) {
            Log::channel('restaurant')->error("Error al eleiminar  mesa {$command->getId()}} \n" .
                "   Clase: " . __CLASS__ . "\n" .
                "   Mensaje: " . $e->getMessage() . "\n" .
                "   Línea: " . $e->getLine() . "\n" .
                "   Trace:\n" . collect($e->getTrace())
                ->take(3)
                ->map(function ($trace, $i) {
                    return "    #$i " . ($trace['file'] ?? '') . ':' . ($trace['line'] ?? '') . ' → ' . ($trace['function'] ?? '');
                })
                ->implode("\n") . "\n");
            return $respuesta;
        }
        return $respuesta;
    }

    private function validateOrFail(int $id): void
    {
        if ($id <= 0 || !$this->tableRepository->exist($id)) {
            throw new \Exception("ID mesa inválido");
        }

        $command = new GetOrderCommand($id);
        $vuelta = $this->orderRepository->getOrderTableDelete($command);
        if ($vuelta->getId() > 0) {
            throw new \Exception("La mesa está en uso");
        }
    }
}
