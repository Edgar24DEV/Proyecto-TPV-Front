<?php

namespace App\Application\OrderLine\UseCases;

use App\Application\OrderLine\DTO\DeleteOrderLineCommand;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use App\Infrastructure\Repositories\EloquentOrderLineRepository;
use Illuminate\Support\Facades\Log;

class DeleteOrderLineUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentOrderLineRepository $orderLineRepository,
    ) {
    }
    public function __invoke(DeleteOrderLineCommand $command): bool
    {
        $this->validateOrFail($command->getId());

        $respuesta = $this->orderLineRepository->delete($command->getId());

        return $respuesta;
    }

    private function validateOrFail(int $id): void
    {
        if ($id <= 0 || !$this->orderLineRepository->exist($id)) {
            throw new \Exception("ID linea pedido inválido");
        }
    }
}