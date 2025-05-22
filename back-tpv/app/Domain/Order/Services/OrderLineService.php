<?php
namespace App\Domain\Order\Services;

use App\Domain\Order\Entities\OrderLine;
use Illuminate\Support\Collection;

class OrderLineService
{
    public function showOrderLineInfo(Collection $orderLine): Collection
    {
        return $orderLine->map(function ($row) {
            return new OrderLine(
                id: $row->id,
                idPedido: $row->idPedido,  // Asegúrate de usar idPedido
                idProducto: $row->idProducto,
                cantidad: $row->cantidad,
                precio: $row->precio,
                nombre: $row->nombre,
                observaciones: $row->observaciones,
                estado: $row->estado
            );
        });
    }

    public function showOrderLineInfoSimple(OrderLine $row): OrderLine
    {
        return new OrderLine(
            id: $row->id,
            idPedido: $row->idPedido,
            idProducto: $row->idProducto,
            cantidad: $row->cantidad,
            precio: $row->precio,
            nombre: $row->nombre,
            observaciones: $row->observaciones,
            estado: $row->estado
        );
    }
}
