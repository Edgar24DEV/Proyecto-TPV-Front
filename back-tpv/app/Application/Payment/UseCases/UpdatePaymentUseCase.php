<?php

namespace App\Application\Payment\UseCases;

use App\Application\Payment\DTO\UpdatePaymentCommand;
use App\Domain\Order\Entities\Payment;
use App\Infrastructure\Repositories\EloquentBillRepository;
use App\Infrastructure\Repositories\EloquentPaymentRepository;

class UpdatePaymentUseCase
{
    public function __construct(
        private readonly EloquentPaymentRepository $paymentRepository,
        private readonly EloquentBillRepository $eloquentBillRepository
    ) {}

    public function __invoke(UpdatePaymentCommand $command): Payment
    {
        // 1. Validar el comando
        $this->validateCommand($command);

        // 2. Si el número de factura es nulo, generar uno con el CIF
        if (!$command->getNFactura()) {
            $numeroFactura = $this->eloquentBillRepository->generateOnlyNumber($command->getCIF());
            // Actualizar el comando con el número de factura generado
            $command = new UpdatePaymentCommand(
                idPago: $command->getIdPago(),
                idCliente: $command->getIdCliente(),
                razonSocial: $command->getRazonSocial(),
                CIF: $command->getCIF(),
                nFactura: $numeroFactura, // Asignar el número de factura generado
                correo: $command->getCorreo(),
                direccionFiscal: $command->getDireccionFiscal()
            );
        }

        // 3. Realizar la actualización del pago con el comando modificado
        $payment = $this->paymentRepository->update($command);

        return $payment;
    }

    private function validateCommand(UpdatePaymentCommand $command): void
    {
        // Validación del ID de pago
        if ($command->getIdPago() <= 0 || !$this->paymentRepository->exist($command->getIdPago())) {
            throw new \InvalidArgumentException("ID pago inválido");
        }

        // Validar que el CIF no sea nulo si es necesario para la generación de factura
        if ($command->getCIF() === null || empty($command->getCIF())) {
            throw new \InvalidArgumentException("CIF es necesario para generar el número de factura");
        }
    }
}
