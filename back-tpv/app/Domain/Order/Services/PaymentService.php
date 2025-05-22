<?php

namespace App\Domain\Order\Services;

use App\Application\Payment\DTO\AddPaymentCommand;
use App\Domain\Order\Entities\Payment;
class PaymentService
{

    public function showPaymentInfo($payments)
    {

        return collect($payments)->map(function ($payment) {
            return new Payment(
                id:$payment['id'],
                tipo: $payment['tipo'],
                cantidad: $payment['cantidad'],
                fecha: $payment['fecha'],
                idPedido: $payment['id_pedido'],
                idCliente: $payment['id_cliente'],
                razonSocial: $payment['razon_social'],
                CIF: $payment['cif'],
                nFactura: $payment['n_factura'],
                correo: $payment['correo'],
                direccionFiscal: $payment['direccion_fiscal'],
            );
        });

    }




}