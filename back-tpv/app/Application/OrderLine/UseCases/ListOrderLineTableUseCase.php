<?php


namespace App\Application\OrderLine\UseCases;

use App\Application\OrderLine\DTO\ListOrderLineTableCommand;
use App\Domain\Order\Services\OrderLineService;
use App\Infrastructure\Repositories\EloquentOrderLineRepository;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use Illuminate\Support\Collection;

final class ListOrderLineTableUseCase
{
    public function __construct(
        private readonly EloquentOrderLineRepository $orderLineRepository,
        private readonly OrderLineService $orderLineService,
        private readonly EloquentOrderRepository $orderRepository,
    ) {
    }

    public function __invoke(ListOrderLineTableCommand $command): Collection
    {
        $idOrderLine = $command->getIdOrderLine();
        $this->validateOrFail($idOrderLine);

        // Recuperar las líneas de pedido
        $orders = $this->orderLineRepository->find($idOrderLine);

        // Procesar las líneas de pedido con el servicio
        $orders = $this->orderLineService->showOrderLineInfo(collect($orders));

        return $orders;
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
