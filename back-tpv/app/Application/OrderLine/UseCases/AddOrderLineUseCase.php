<?php


namespace App\Application\OrderLine\UseCases;

use App\Application\OrderLine\DTO\AddOrderLineCommand;
use App\Application\OrderLine\DTO\ListOrderLineTableCommand;
use App\Domain\Order\Entities\OrderLine;
use App\Domain\Order\Services\OrderLineService;
use App\Infrastructure\Repositories\EloquentOrderLineRepository;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

final class AddOrderLineUseCase
{
    public function __construct(
        private readonly EloquentOrderLineRepository $orderLineRepository,
        private readonly OrderLineService $orderLineService,
        private readonly EloquentOrderRepository $orderRepository,
    ) {
    }

    public function __invoke(AddOrderLineCommand $command): OrderLine
    {
        $this->validateOrFail($command->getIdOrder());

        try {
            // 1. Buscar si ya existe la línea con ese producto en ese pedido
            $existingLine = $this->orderLineRepository->findByOrderAndProduct(
                $command->getIdOrder(),
                $command->getIdProduct()
            );

            if ($existingLine) {
                // 2. Si existe, actualizar la cantidad sumando
                $updatedQuantity = $existingLine->cantidad + $command->getQuantity();

                $updateCommand = new \App\Application\OrderLine\DTO\UpdateOrderLineCommand(
                    id: $existingLine->id,
                    quantity: $updatedQuantity,
                    name: $existingLine->nombre, // opcional
                    price: $existingLine->precio, // opcional
                );

                $updatedLine = $this->orderLineRepository->update($updateCommand);
                return $this->orderLineService->showOrderLineInfoSimple($updatedLine);
            }

            // 3. Si no existe, crearla como ya hacías
            $orderLine = $this->orderLineRepository->create($command);
            return $this->orderLineService->showOrderLineInfoSimple($orderLine);

        } catch (\Exception $e) {
            Log::channel('order')->warning(
                "Fallo al crear la linea de pedido \n" .
                "   Clase: " . __CLASS__ . "\n" .
                "   Mensaje: " . $e->getMessage() . "\n" .
                "   Línea: " . $e->getLine() . "\n" .
                "   Trace:\n" . collect($e->getTrace())
                    ->take(3)
                    ->map(function ($trace, $i) {
                        return "    #$i " . ($trace['file'] ?? '') . ':' . ($trace['line'] ?? '') . ' → ' . ($trace['function'] ?? '');
                    })
                    ->implode("\n") . "\n"
            );
            return new OrderLine(
                id: -1,
                idPedido: -1,
                idProducto: -1,
                cantidad: -1,
                precio: -1,
                nombre: "",
                observaciones: $e->getMessage(),
                estado: "null",
            );
        }
    }

    private function validateOrFail(int $idOrderLine): void
    {
        if ($idOrderLine <= 0) {
            throw new \InvalidArgumentException("ID inválido");
        }

        if (!$this->orderRepository->exist($idOrderLine)) {
            throw new \InvalidArgumentException("ID No existe");
        }
    }
}
