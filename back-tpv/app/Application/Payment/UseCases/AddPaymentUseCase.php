<?php

namespace App\Application\Payment\UseCases;

use App\Application\Payment\DTO\AddPaymentCommand;
use App\Application\Product\DTO\AddProductCommand;
use App\Domain\Order\Entities\Payment;
use App\Domain\Product\Entities\Product;
use App\Infrastructure\Repositories\EloquentCategoryRepository;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use App\Infrastructure\Repositories\EloquentPaymentRepository;
use App\Infrastructure\Repositories\EloquentProductRepository;

class AddPaymentUseCase
{
    public function __construct(
        private readonly EloquentOrderRepository $orderRepository,
        private readonly EloquentPaymentRepository $paymentRepository,
    ) {
    }

    public function __invoke(AddPaymentCommand $command): Payment
    {

        $this->validateCommand($command);


        $this->checkDependenciesExist($command);


        $payment = $this->paymentRepository->save($command);

        return $payment;
    }

    private function validateCommand(AddPaymentCommand $command): void
    {
        if (empty($command->getTipo())) {
            throw new \InvalidArgumentException('No se ha podido leer el tipo de pago');
        }

        if ($command->getCantidad() === null || $command->getCantidad() < 0) {
            throw new \InvalidArgumentException('El total debe ser un valor positivo');
        }

    }

    private function checkDependenciesExist(AddPaymentCommand $command): void
    {

        if (!$this->orderRepository->exist($command->getIdPedido())) {
            throw new \InvalidArgumentException('No se encuentra el pedido del que se quiere hacer el pago');
        }
    }
}