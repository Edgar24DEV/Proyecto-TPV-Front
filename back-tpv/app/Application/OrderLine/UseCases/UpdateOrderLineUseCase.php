<?php

namespace App\Application\orderLine\UseCases;

use App\Application\OrderLine\DTO\UpdateorderLineCommand;
use App\Domain\Order\Entities\OrderLine;
use App\Domain\Order\Services\OrderLineService;
use App\Infrastructure\Repositories\EloquentOrderLineRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;


class UpdateOrderLineUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentOrderLineRepository $orderLineRepository,
        private readonly OrderLineService $orderLineService,
    ) {
    }
    public function __invoke(UpdateOrderLineCommand $command): OrderLine
    {
        $this->validateOrFail(
            $command->getId(),
            $command->getName(),
            $command->getPrice(),
            $command->getQuantity()
        );

        // $orderLine = $this->orderLineService->requestorderLine($command);
        try {
            $orderLine = $this->orderLineRepository->update($command);
            $orderLineInfo = $this->orderLineService->showOrderLineInfoSimple($orderLine);
        } catch (\Exception $e) {

            $orderLineVacio = new OrderLine(
                id: -1,
                idPedido: -1,
                idProducto: -1,
                cantidad: -1,
                precio: -1,
                nombre: "",
                observaciones: $e,
                estado: "null",
            );
            return $orderLineVacio;
        }
        return $orderLineInfo;
    }


    private function validateOrFail(int $id, string $name, float $price, int $quantity): void
    {
        if ($id <= 0 || !$this->orderLineRepository->exist($id)) {
            throw new \Exception("ID linea pedido inválido");
        }
        if ($name === "") {
            throw new \Exception("Nombre de producto no valido");
        }
        if ($price < 0 || $quantity < 0) {
            throw new \Exception("Cantidad o precio no valido");
        }
    }
}