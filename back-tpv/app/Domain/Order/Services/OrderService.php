<?php
namespace App\Domain\Order\Services;
use App\Domain\Order\Entities\Order;
use Illuminate\Http\Request;
class OrderService
{

    public function showOrderInfo($categories)
    {

        return collect($categories)->map(function ($row) {
            return new Order(
                id: $row->id,
                estado: $row->estado,
                fechaInicio: $row->fechaInicio,
                fechaFin: $row->fechaFin,
                comensales: $row->comensales,
                idMesa: $row->idMesa,
            );
        });
    }
    public function showOrderInfoSimple($order)
    {
        return new Order(
            id: $order->id,
            estado: $order->estado,
            fechaInicio: $order->fechaInicio,
            fechaFin: $order->fechaFin,
            comensales: $order->comensales,
            idMesa: $order->idMesa,
        );
        
    }

  
}