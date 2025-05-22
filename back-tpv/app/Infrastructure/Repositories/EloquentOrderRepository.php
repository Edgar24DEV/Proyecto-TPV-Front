<?php

namespace App\Infrastructure\Repositories;

use App\Application\Order\DTO\GetCompanyOrdersCommand;
use App\Application\Order\DTO\GetOngoingOrdersCommand;
use App\Application\Order\DTO\GetRestaurantOrdersCommand;
use App\Application\Order\DTO\UpdateOrderDinersCommand;
use App\Application\Order\DTO\AddOrderCommand;
use App\Application\Order\DTO\GetOrderCommand;
use App\Application\Order\DTO\UpdateOrderStatusCommand;
use App\Application\Order\Handlers\GetRestaurantOrdersCommandHandler;
use App\Domain\Order\Repositories\OrderRepositoryInterface;
use App\Domain\Order\Entities\Order;
use App\Infrastructure\Http\Controllers\PutOrderStatusController;
use Illuminate\Support\Facades\DB;
use Termwind\ValueObjects\Node;

class EloquentOrderRepository implements OrderRepositoryInterface
{
    public function exist(int $idPedido): bool
    {
        $results = DB::table('pedidos')
            ->where('id', $idPedido)
            ->whereNull('deleted_at')
            ->exists();

        return $results;
    }

    public function delete(int $id): bool
    {
        $delete = DB::table('pedidos')
            ->where('id', $id)
            ->update(['deleted_at' => now()]);

        return $delete > 0;
    }

    public function create(AddOrderCommand $command): Order
    {

        $id = DB::table('pedidos')->insertGetId([
            'estado' => 'Abierto',
            'fecha_inicio' => now(),
            'fecha_fin' => null,
            'comensales' => $command->getComensales(),
            'id_mesa' => $command->getIdMesa(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // Retorna una nueva instancia con el ID generado
        return new Order(
            id: $id,
            estado: 'Abierto',
            fechaInicio: now(),
            fechaFin: null,
            comensales: $command->getComensales(),
            idMesa: $command->getIdMesa(),
        );

    }

    public function updateDiners(UpdateOrderDinersCommand $order): Order
    {
        $orderId = $order->getId();

        // 1. Actualizamos
        DB::table('pedidos')->where('id', $orderId)
            ->update([
                'comensales' => $order->getComensales(),
                'updated_at' => now(),
            ]);

        // 2. Recuperamos los datos actualizados
        $row = DB::table('pedidos')->where('id', $orderId)->first();

        // 3. Creamos y devolvemos el objeto Order
        return new Order(
            id: $row->id,
            estado: $row->estado,
            fechaInicio: $row->fecha_inicio ? new \DateTime($row->fecha_inicio) : null,
            fechaFin: $row->fecha_fin ? new \DateTime($row->fecha_fin) : null,
            comensales: $row->comensales,
            idMesa: $row->id_mesa,
        );
    }


    public function getOrder(GetOrderCommand $command): Order
    {
        $pedido = DB::table('pedidos')
        ->where('id_mesa', '=', $command->getIdMesa())
        ->whereIn('estado', ['Pendiente', 'Abierto'])
        ->where('fecha_inicio', '>=', now()->subHours(24)) // Solo pedidos de las últimas 24 horas
        ->orderBy('fecha_inicio', 'desc')
        ->first();
        //dd($pedido);

        return new Order(
            id: $pedido->id,
            estado: $pedido->estado,
            fechaInicio: $pedido->fecha_inicio ? new \DateTime($pedido->fecha_inicio) : null,
            fechaFin: $pedido->fecha_fin ? new \DateTime($pedido->fecha_fin) : null,
            comensales: $pedido->comensales,
            idMesa: $pedido->id_mesa
        );


        // // Si no hay pedidos activos o pendientes, lanzamos excepción para que el UseCase lo maneje
        // throw new \Exception("No hay pedido activo o pendiente para esta mesa.");
    }

    public function getOngoingOrders(GetOngoingOrdersCommand $command): array
    {
        $pedidos = DB::table('pedidos')
            ->join('mesas', 'mesas.id', '=', 'pedidos.id_mesa')
            ->join('ubicaciones', 'ubicaciones.id', '=', 'mesas.id_ubicacion')
            ->where('ubicaciones.id_restaurante', '=', $command->getIdRestaurante())
            ->where('pedidos.estado', 'LIKE', 'Abierto')
            ->orWhere('pedidos.estado', 'LIKE', 'Pendiente')
            ->whereNull('mesas.deleted_at')
            ->whereNull('ubicaciones.deleted_at')
            ->whereNull('pedidos.deleted_at')
            ->get();
        //dd($pedido);
        return $pedidos->map(function ($pedido) {
            return new Order(
                id: $pedido->id,
                estado: $pedido->estado,
                fechaInicio: $pedido->fecha_inicio ? new \DateTime($pedido->fecha_inicio) : null,
                fechaFin: $pedido->fecha_fin ? new \DateTime($pedido->fecha_fin) : null,
                comensales: $pedido->comensales,
                idMesa: $pedido->id_mesa
            );
        })->toArray();


        // // Si no hay pedidos activos o pendientes, lanzamos excepción para que el UseCase lo maneje
        // throw new \Exception("No hay pedido activo o pendiente para esta mesa.");
    }

    public function getRestaurantOrders(GetRestaurantOrdersCommand $command): array
    {
        $pedidos = DB::table('pedidos')
            ->join('mesas', 'mesas.id', '=', 'pedidos.id_mesa')
            ->join('ubicaciones', 'ubicaciones.id', '=', 'mesas.id_ubicacion')
            ->where('ubicaciones.id_restaurante', '=', $command->getIdRestaurante())
            ->whereNull('mesas.deleted_at')
            ->whereNull('ubicaciones.deleted_at')
            ->whereNull('pedidos.deleted_at')
            ->select([
                'pedidos.id as pedido_id',
                'pedidos.estado',
                'pedidos.fecha_inicio',
                'pedidos.fecha_fin',
                'pedidos.comensales',
                'pedidos.id_mesa',
            ])
            ->orderByDesc('fecha_inicio')
            ->get();
        //dd($pedido);
        return $pedidos->map(function ($pedido) {
            return new Order(
                id: $pedido->pedido_id,
                estado: $pedido->estado,
                fechaInicio: $pedido->fecha_inicio ? new \DateTime($pedido->fecha_inicio) : null,
                fechaFin: $pedido->fecha_fin ? new \DateTime($pedido->fecha_fin) : null,
                comensales: $pedido->comensales,
                idMesa: $pedido->id_mesa
            );
        })->toArray();


        // // Si no hay pedidos activos o pendientes, lanzamos excepción para que el UseCase lo maneje
        // throw new \Exception("No hay pedido activo o pendiente para esta mesa.");
    }

    public function getCompanyOrders(GetCompanyOrdersCommand $command): array
    {
        $pedidos = DB::table('pedidos')
    ->join('mesas', 'mesas.id', '=', 'pedidos.id_mesa')
    ->join('ubicaciones', 'ubicaciones.id', '=', 'mesas.id_ubicacion')
    ->join('restaurantes', 'restaurantes.id', '=', 'ubicaciones.id_restaurante')
    ->where('restaurantes.id_empresa', '=', $command->getIdEmpresa())
    ->whereNull('mesas.deleted_at')
    ->whereNull('ubicaciones.deleted_at')
    ->whereNull('pedidos.deleted_at')
    ->whereNull('restaurantes.deleted_at')
    ->select([
        'pedidos.id as pedido_id',
        'pedidos.estado',
        'pedidos.fecha_inicio',
        'pedidos.fecha_fin',
        'pedidos.comensales',
        'pedidos.id_mesa',
    ])
    ->orderByDesc('fecha_inicio')
    ->get();
        return $pedidos->map(function ($pedido) {
            return new Order(
                id: $pedido->pedido_id,
                estado: $pedido->estado,
                fechaInicio: $pedido->fecha_inicio ? new \DateTime($pedido->fecha_inicio) : null,
                fechaFin: $pedido->fecha_fin ? new \DateTime($pedido->fecha_fin) : null,
                comensales: $pedido->comensales,
                idMesa: $pedido->id_mesa
            );
        })->toArray();

        // // Si no hay pedidos activos o pendientes, lanzamos excepción para que el UseCase lo maneje
        // throw new \Exception("No hay pedido activo o pendiente para esta mesa.");
    }

    public function findByID(UpdateOrderStatusCommand $command): Order
    {
        $pedido = DB::table('pedidos')
            ->where('id', '=', $command->getId())
            ->orderBy('fecha_inicio', 'desc')
            ->whereNull('deleted_at')
            ->first();
        //dd($pedido);

        return new Order(
            id: $pedido->id,
            estado: $pedido->estado,
            fechaInicio: $pedido->fecha_inicio ? new \DateTime($pedido->fecha_inicio) : null,
            fechaFin: $pedido->fecha_fin ? new \DateTime($pedido->fecha_fin) : null,
            comensales: $pedido->comensales,
            idMesa: $pedido->id_mesa
        );
    }
    public function updateStatus(UpdateOrderStatusCommand $order): Order
    {
        $orderId = $order->getId();
        DB::table('pedidos')->where('id', $orderId)
            ->update([
                'estado' => $order->getEstado(),
                'updated_at' => now(),
            ]);

        if (
            ucfirst(strtolower($order->getEstado())) == 'Cerrado' ||
            ucfirst(strtolower($order->getEstado())) == 'Cancelado'
        ) {
            DB::table('pedidos')->where('id', $orderId)
                ->update([
                    'fecha_fin' => now(),
                ]);
        }
        // 2. Recuperamos los datos actualizados
        $row = DB::table('pedidos')->where('id', $orderId)->first();

        // 3. Creamos y devolvemos el objeto Order
        return new Order(
            id: $row->id,
            estado: $row->estado,
            fechaInicio: $row->fecha_inicio ? new \DateTime($row->fecha_inicio) : null,
            fechaFin: $row->fecha_fin ? new \DateTime($row->fecha_fin) : null,
            comensales: $row->comensales,
            idMesa: $row->id_mesa,
        );
    }
    public function getOrderTableDelete(GetOrderCommand $command): Order
    {
        $pedido = DB::table('pedidos')
        ->where('id_mesa', '=', $command->getIdMesa())
        ->whereIn('estado', ['Pendiente', 'Abierto'])
        ->where('fecha_inicio', '>=', now()->subHours(24))
        ->orderBy('fecha_inicio', 'desc')
        ->first();
        //dd($pedido);
        if (!$pedido) {
            return new Order(
                id: -1,
                estado: "",
                fechaInicio:  null,
                fechaFin:  null,
                comensales: -2,
                idMesa: -5
            );
        }

        return new Order(
            id: $pedido->id,
            estado: $pedido->estado,
            fechaInicio: $pedido->fecha_inicio ? new \DateTime($pedido->fecha_inicio) : null,
            fechaFin: $pedido->fecha_fin ? new \DateTime($pedido->fecha_fin) : null,
            comensales: $pedido->comensales,
            idMesa: $pedido->id_mesa
        );


        // // Si no hay pedidos activos o pendientes, lanzamos excepción para que el UseCase lo maneje
        // throw new \Exception("No hay pedido activo o pendiente para esta mesa.");
    }

}
