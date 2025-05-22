<?php

namespace App\Application\Payment\UseCases;

use App\Application\Payment\DTO\DeletePaymentCommand;
use App\Infrastructure\Repositories\EloquentPaymentRepository;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;

class DeletePaymentUseCase
{
    use ApiResponseTrait;
    public function __construct(
        private readonly EloquentPaymentRepository $paymentRepository,
    ) {
    }
    public function __invoke(DeletePaymentCommand $command): bool
    {
        $this->validateOrFail($command->getId());

        $respuesta = $this->paymentRepository->delete($command->getId());

        return $respuesta;
    }

    private function validateOrFail(int $id): void
    {
        if ($id <= 0 || !$this->paymentRepository->exist($id)) {
            throw new \InvalidArgumentException("ID de pago inválido");
        }
    }
}