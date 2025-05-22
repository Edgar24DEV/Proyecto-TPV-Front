<?php

namespace App\Infrastructure\Repositories;

use App\Application\Payment\DTO\AddPaymentCommand;
use App\Application\Payment\DTO\UpdatePaymentBillCommand;
use App\Application\Payment\DTO\UpdatePaymentCommand;
use App\Domain\Order\Entities\Payment;
use App\Domain\Order\Repositories\PaymentRepositoryInterface;

use Illuminate\Support\Facades\DB;

class EloquentPaymentRepository implements PaymentRepositoryInterface
{

    private $numeroFactura = null;
    public function exist(int $id): bool
    {

        $results = DB::table('pagos')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->exists();

        return $results;
    }

    public function delete(int $id): bool
    {
        $delete = DB::table('pagos')
            ->where('id', $id)
            ->update(['deleted_at' => now()]);

        return $delete > 0;
    }

    public function findAll(int $restaurantId): array
    {
        $query = DB::table('pagos')
            ->join('pedidos', 'pagos.id_pedido', '=', 'pedidos.id')
            // Relacionamos la mesa con la ubicación y el restaurante
            ->join('mesas', 'pedidos.id_mesa', '=', 'mesas.id')
            ->join('ubicaciones', 'mesas.id_ubicacion', '=', 'ubicaciones.id')
            ->where('ubicaciones.id_restaurante', $restaurantId)
            ->whereNull('pedidos.deleted_at')
            ->whereNull('pagos.deleted_at')
            ->whereNull('mesas.deleted_at')
            ->whereNull('ubicaciones.deleted_at');


        $paymentsDb = $query->get();

        return $paymentsDb->map(function ($payment) {
            return (new AddPaymentCommand(
                tipo: $payment->tipo,
                cantidad: $payment->cantidad,
                fecha: $payment->fecha,
                idPedido: $payment->id_pedido,
                idCliente: $payment->id_cliente,
                razonSocial: $payment->razon_social,
                CIF: $payment->CIF,
                nFactura: $payment->n_factura,
                correo: $payment->correo,
                direccionFiscal: $payment->direccion_fiscal
            ))->toArray();
        })->toArray();

    }

    public function findByOrder(int $idOrder): array
    {
        $query = DB::table('pagos')
            ->where('id_pedido', $idOrder)
            ->whereNull('deleted_at');


        $paymentsDb = $query->get();

        return $paymentsDb->map(function ($payment) {
            return (new Payment(
                id: $payment->id,
                tipo: $payment->tipo,
                cantidad: $payment->cantidad,
                fecha: $payment->fecha,
                idPedido: $payment->id_pedido,
                idCliente: $payment->id_cliente,
                razonSocial: $payment->razon_social,
                CIF: $payment->CIF,
                nFactura: $payment->n_factura,
                correo: $payment->correo,
                direccionFiscal: $payment->direccion_fiscal
            ))->toArray();
        })->toArray();

    }

    public function findByClient(int $idClient): array
    {
        $query = DB::table('pagos')
            ->where('id_cliente', $idClient)
            ->whereNull('pagos.deleted_at');


        $paymentsDb = $query->get();

        return $paymentsDb->map(function ($payment) {
            return (new Payment(
                id: $payment->id,
                tipo: $payment->tipo,
                cantidad: $payment->cantidad,
                fecha: $payment->fecha,
                idPedido: $payment->id_pedido,
                idCliente: $payment->id_cliente,
                razonSocial: $payment->razon_social,
                CIF: $payment->CIF,
                nFactura: $payment->n_factura,
                correo: $payment->correo,
                direccionFiscal: $payment->direccion_fiscal
            ))->toArray();
        })->toArray();

    }



    public function save(AddPaymentCommand $command): Payment
    {
        $fecha = $command->getFecha() ?? now();
        $añoActual = date('Y', strtotime($fecha));
        $añoCorto = date('y', strtotime($fecha));

        if ($command->getCIF() !== null) {
            // Contar solo los pagos de este año
            $count = DB::table('pagos')
                ->whereNotNull('CIF')
                ->whereYear('fecha', $añoActual)
                ->whereNull('deleted_at')
                ->count();

            $this->numeroFactura = ($count + 1) . "/" . $añoCorto;
        }

        $id = DB::table('pagos')->insertGetId([
            'tipo' => $command->getTipo(),
            'cantidad' => $command->getCantidad(),
            'fecha' => $fecha,
            'id_pedido' => $command->getIdPedido(),
            'id_cliente' => $command->getIdCliente() ?? null,
            'razon_social' => $command->getRazonSocial() ?? null,
            'CIF' => $command->getCIF() ?? null,
            'n_factura' => $this->numeroFactura,
            'correo' => $command->getCorreo() ?? null,
            'direccion_fiscal' => $command->getDireccionFiscal() ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $result = DB::table('pagos')
            ->where('id', '=', $id)
            ->whereNull('deleted_at')
            ->first();

        return new Payment(
            id: $result->id,
            tipo: $result->tipo,
            cantidad: $result->cantidad,
            fecha: $result->fecha,
            idPedido: $result->id_pedido,
            idCliente: $result->id_cliente,
            razonSocial: $result->razon_social,
            CIF: $result->CIF,
            nFactura: $result->n_factura,
            correo: $result->correo,
            direccionFiscal: $result->direccion_fiscal,
        );
    }

    public function update(UpdatePaymentCommand $command): Payment
    {
        $updated = DB::table('pagos')
            ->where('id', $command->getIdPago())
            ->whereNull('deleted_at')
            ->update([
                'id_cliente' => $command->getIdCliente(),
                'razon_social' => $command->getRazonSocial(),
                'CIF' => $command->getCIF(),
                'n_factura' => $command->getNFactura(),
                'correo' => $command->getCorreo(),
                'direccion_fiscal' => $command->getDireccionFiscal(),
                'updated_at' => now(),
            ]);

        if ($updated === 0) {
            throw new \Exception("No se actualizó el pago.");
        }

        $result = DB::table('pagos')
            ->where('id', $command->getIdPago())
            ->whereNull('deleted_at')
            ->first();

        if (!$result) {
            throw new \Exception("Pago no encontrado.");
        }

        return new Payment(
            id: $result->id,
            tipo: $result->tipo,
            cantidad: $result->cantidad,
            fecha: $result->fecha,
            idPedido: $result->id_pedido,
            idCliente: $result->id_cliente,
            razonSocial: $result->razon_social,
            CIF: $result->CIF,
            nFactura: $result->n_factura,
            correo: $result->correo,
            direccionFiscal: $result->direccion_fiscal
        );
    }

    public function updateBill(UpdatePaymentBillCommand $command): Payment
    {

        $query = DB::table('pagos')
            ->where('id_pedido', $command->getIdPedido())
            ->whereNull('deleted_at')->first();

        $fecha = $query->fecha ?? now();
        $añoActual = date('Y', strtotime($fecha));
        $añoCorto = date('y', strtotime($fecha));

        if ($command->getCIF() !== null) {
            // Contar solo los pagos de este año
            $count = DB::table('pagos')
                ->whereNotNull('CIF')
                ->whereYear('fecha', $añoActual)
                ->whereNull('deleted_at')
                ->count();

            $this->numeroFactura = ($count + 1) . "/" . $añoCorto;
        }

        $updated = DB::table('pagos')
            ->where('id_pedido', $command->getIdPedido())
            ->whereNull('deleted_at')
            ->update([
                'id_cliente' => $command->getIdCliente(),
                'razon_social' => $command->getRazonSocial(),
                'CIF' => $command->getCIF(),
                'n_factura' => $this->numeroFactura,
                'correo' => $command->getCorreo(),
                'direccion_fiscal' => $command->getDireccionFiscal(),
                'updated_at' => now(),
            ]);

        if ($updated === 0) {
            throw new \Exception("No se actualizó el pago.");
        }

        $result = DB::table('pagos')
            ->where('id_pedido', $command->getIdPedido())
            ->whereNull('deleted_at')
            ->first();

        if (!$result) {
            throw new \Exception("Pago no encontrado.");
        }

        return new Payment(
            id: $result->id,
            tipo: $result->tipo,
            cantidad: $result->cantidad,
            fecha: $result->fecha,
            idPedido: $result->id_pedido,
            idCliente: $result->id_cliente,
            razonSocial: $result->razon_social,
            CIF: $result->CIF,
            nFactura: $result->n_factura,
            correo: $result->correo,
            direccionFiscal: $result->direccion_fiscal
        );
    }


}
