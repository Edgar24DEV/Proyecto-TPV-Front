<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Order\Entities\Order;
use App\Domain\Order\Entities\OrderLine;
use App\Domain\Order\Repositories\TicketRepositoryInterface;
use App\Domain\Restaurant\Entities\Restaurant;
use Illuminate\Support\Facades\DB;

class EloquentTicketRepository implements TicketRepositoryInterface
{
    public function getOrderWithLines(int $idPedido, int $idRestaurante): array
    {
        $pedidoDb = DB::table('pedidos')
            ->where('id', $idPedido)
            ->whereNull('deleted_at')
            ->first();

        $restauranteDb = DB::table('restaurantes')
            ->where('id', $idRestaurante)
            ->whereNull('deleted_at')
            ->first();

        $lineasDb = DB::table('lineas_pedido')
            ->where('id_pedido', $idPedido)
            ->get();

        if (!$pedidoDb) {
            throw new \Exception("Pedido con ID {$idPedido} no encontrado");
        }

        if (!$restauranteDb) {
            throw new \Exception("Restaurante con ID {$idRestaurante} no encontrado");
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
        ];
    }
}
