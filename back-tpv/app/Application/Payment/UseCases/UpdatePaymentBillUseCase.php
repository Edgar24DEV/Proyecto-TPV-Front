<?php

namespace App\Application\Payment\UseCases;

use App\Application\Payment\DTO\UpdatePaymentBillCommand;
use App\Application\Payment\DTO\UpdatePaymentCommand;
use App\Domain\Order\Entities\Payment;
use App\Infrastructure\Repositories\EloquentBillRepository;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use App\Infrastructure\Repositories\EloquentPaymentRepository;
use App\Infrastructure\Repositories\EloquentUpdateBillRepository;

class UpdatePaymentBillUseCase
{
    public function __construct(
        private readonly EloquentPaymentRepository $paymentRepository,
        private readonly EloquentOrderRepository $orderRepository,
        private readonly EloquentUpdateBillRepository $eloquentBillRepository
    ) {
    }

    public function __invoke(UpdatePaymentBillCommand $command): Payment
    {
        // 1. Validar el comando
        $this->validateCommand($command);


        // Actualizar el comando con el número de factura generado
        $command = new UpdatePaymentBillCommand(
            idPedido: $command->getIdPedido(),
            idCliente: $command->getIdCliente(),
            razonSocial: $command->getRazonSocial(),
            CIF: $command->getCIF(),
            nFactura: $command->getNFactura(), // Asignar el número de factura generado
            correo: $command->getCorreo(),
            direccionFiscal: $command->getDireccionFiscal()
        );

        // 3. Realizar la actualización del pago con el comando modificado
        $payment = $this->paymentRepository->updateBill($command);

        return $payment;
    }

    private function validateCommand(UpdatePaymentBillCommand $command): void
    {
        // Validación del ID de pago
        if ($command->getIdPedido() <= 0 || !$this->orderRepository->exist($command->getIdPedido())) {
            throw new \Exception("ID pedido inválido");
        }

        // Validar que el CIF no sea nulo si es necesario para la generación de factura
        if ($command->getCIF() === null || empty($command->getCIF())) {
            throw new \Exception("CIF es necesario para generar el número de factura");
        }
    }
}
