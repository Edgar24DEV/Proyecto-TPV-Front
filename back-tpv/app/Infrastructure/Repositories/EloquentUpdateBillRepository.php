<?php

namespace App\Infrastructure\Repositories;

use App\Application\Payment\DTO\AddPaymentCommand;
use App\Application\Payment\DTO\UpdatePaymentBillCommand;
use App\Application\Payment\DTO\UpdatePaymentCommand;
use App\Domain\Company\Entities\Client;
use App\Domain\Order\Entities\Order;
use App\Domain\Order\Entities\OrderLine;
use App\Domain\Order\Repositories\BillRepositoryInterface;
use App\Domain\Restaurant\Entities\Restaurant;
use Illuminate\Support\Facades\DB;

class EloquentUpdateBillRepository implements BillRepositoryInterface
{

    public function __construct(
        private readonly EloquentPaymentRepository $paymentRepository
    ) {
    }
    public function getOrderWithLines(int $idPedido, int $idRestaurante, int $idCliente, float $total, string $tipo): array
    {
        $pedidoDb = DB::table('pedidos')->where('id', $idPedido)->whereNull('deleted_at')->first();
        $restauranteDb = DB::table('restaurantes')
        ->join('ubicaciones' , 'ubicaciones.id_restaurante', '=', 'restaurantes.id')
        ->join("mesas", 'mesas.id_ubicacion', '=', 'ubicaciones.id')
        ->join("pedidos",'pedidos.id_mesa', '=', 'mesas.id')
        ->where('pedidos.id', $idPedido)
        ->whereNull('pedidos.deleted_at')->first();

        $clienteDb = DB::table('clientes')->where('id', $idCliente)->whereNull('deleted_at')->first();
        $lineasDb = DB::table('lineas_pedido')->where('id_pedido', $idPedido)->get();
        $pago = new UpdatePaymentBillCommand(

            $idPedido,
            $idCliente,
            $clienteDb->razon_social,
            $clienteDb->cif,
            null,
            $clienteDb->correo,
            $clienteDb->direccion_fiscal,
        );

        $pago = $this->paymentRepository->updateBill($pago);

        if (!$pedidoDb) {
            throw new \Exception("Pedido con ID {$idPedido} no encontrado");
        }

        $order = new Order(
            id: $pedidoDb->id,
            estado: $pedidoDb->estado,
            comensales: $pedidoDb->comensales,
            idMesa: $pedidoDb->id_mesa,
            fechaInicio: $pedidoDb->fecha_inicio ? new \DateTime($pedidoDb->fecha_inicio) : null,
            fechaFin: $pedidoDb->fecha_fin ? new \DateTime($pedidoDb->fecha_fin) : null,
        );

        $restaurant = new Restaurant(
            id: (int) $restauranteDb->id,
            nombre: (string) $restauranteDb->nombre,
            direccion: (string) $restauranteDb->direccion,
            telefono: (string) $restauranteDb->telefono,
            contrasenya: (string) $restauranteDb->contrasenya,
            direccionFiscal: (string) $restauranteDb->direccion_fiscal,
            cif: (string) $restauranteDb->CIF,
            razonSocial: (string) $restauranteDb->razon_social,
            idEmpresa: (int) $restauranteDb->id_empresa,
        );

        $client = new Client(
            id: (int) $clienteDb->id,
            razon_social: $clienteDb->razon_social,
            cif: $clienteDb->cif,
            direccion_fiscal: $clienteDb->direccion_fiscal,
            correo: $clienteDb->correo,
            id_empresa: $clienteDb->id_empresa,
        );

        $lineas = collect($lineasDb)->map(function ($linea) {
            return new OrderLine(
                id: $linea->id,
                idPedido: $linea->id_pedido,
                idProducto: $linea->id_producto,
                cantidad: $linea->cantidad,
                precio: $linea->precio,
                nombre: $linea->nombre,
                estado: $linea->estado,
                observaciones: $linea->observaciones,
            );
        })->toArray();

        return [
            'pedido' => $order,
            'lineas' => $lineas,
            'restaurante' => $restaurant,
            'cliente' => $client,
            'pago' => $pago,
        ];
    }


}
