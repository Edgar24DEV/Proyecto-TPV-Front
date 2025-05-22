<?php

namespace App\Infrastructure\Repositories;

use App\Application\OrderLine\DTO\AddOrderLineCommand;
use App\Application\OrderLine\DTO\UpdateOrderLineCommand;
use App\Domain\Order\Entities\OrderLine;
use App\Domain\Order\Repositories\OrderLineRepositoryInterface;
use App\Domain\Product\Entities\Product;
use Illuminate\Support\Facades\DB;

class EloquentOrderLineRepository implements OrderLineRepositoryInterface
{
    public function exist(int $orderLine): bool
    {
        return DB::table('lineas_pedido')
            ->where('id', $orderLine)
            ->exists();
    }

    public function findByOrderAndProduct(int $orderId, int $productId): ?OrderLine
    {
        $row = DB::table('lineas_pedido')
            ->where('id_pedido', $orderId)
            ->where('id_producto', $productId)
            ->first();

        if (!$row)
            return null;

        return new OrderLine(
            id: $row->id,
            idPedido: $row->id_pedido,
            idProducto: $row->id_producto,
            cantidad: $row->cantidad,
            precio: $row->precio,
            nombre: $row->nombre,
            observaciones: $row->observaciones,
            estado: $row->estado
        );
    }


    public function find(int $order): array
    {
        // Obtener los resultados de la base de datos
        $results = DB::table('lineas_pedido')
            ->join('pedidos', 'lineas_pedido.id_pedido', '=', 'pedidos.id') // Hacemos el JOIN entre id_pedido y id
            ->where('pedidos.id', $order)
            ->select(
                'lineas_pedido.id as linea_id',
                'lineas_pedido.id_pedido',
                'lineas_pedido.id_producto',
                'lineas_pedido.cantidad',
                'lineas_pedido.precio',
                'lineas_pedido.nombre',
                'lineas_pedido.observaciones',
                'lineas_pedido.estado',
                'pedidos.id as pedido_id'
            )
            ->get();

        // Convertir los resultados en instancias de OrderLine
        return $results->map(function ($row) {
            return new OrderLine(
                id: $row->linea_id,           // id de la línea
                idPedido: $row->pedido_id,    // id del pedido
                idProducto: $row->id_producto,
                cantidad: $row->cantidad,
                precio: $row->precio,
                nombre: $row->nombre,
                observaciones: $row->observaciones,
                estado: $row->estado
            );
        })->toArray();

    }

    public function create(AddOrderLineCommand $command): OrderLine
    {

        $product = $this->findProduct($command->getIdProduct());

        $orderLine = DB::table('lineas_pedido')->insertGetId([
            'id_pedido' => $command->getIdOrder(),
            'id_producto' => $command->getIdProduct(),
            'cantidad' => $command->getQuantity(),
            'precio' => $product->getPrecio(),
            'nombre' => $product->getNombre(),
            'observaciones' => $command->getDescription(),
            'estado' => $command->getState(),
            'created_at' => Now(),
            'updated_at' => Now(),
        ]);



        // Retorna una nueva instancia con el ID generado
        return new OrderLine(
            id: $orderLine,
            idPedido: $command->getIdOrder(),
            idProducto: $command->getIdProduct(),
            cantidad: $command->getQuantity(),
            precio: $product->getPrecio(),
            nombre: $product->getNombre(),
            observaciones: $command->getDescription(),
            estado: $command->getState()
        );

    }

    public function findProduct(int $idProduct): Product
    {


        $result = DB::table('productos')
            ->where('id', $idProduct)
            ->select('*')
            ->first();

        return new Product(
            id: $result->id,
            nombre: $result->nombre,
            precio: $result->precio,
            imagen: $result->imagen,
            activo: $result->activo,
            iva: $result->iva,
            idCategoria: $result->id_categoria,
            idEmpresa: $result->id_empresa,
        );

    }

    public function update(UpdateOrderLineCommand $orderLine): OrderLine
    {
        $orderLineId = $orderLine->getId();
        DB::table('lineas_pedido')->where('id', $orderLineId)
            ->update([
                'nombre' => $orderLine->getName(),
                'cantidad' => $orderLine->getQuantity(),
                'precio' => $orderLine->getPrice(),
                'updated_at' => now(),
            ]);

        $row = DB::table('lineas_pedido')->where('id', $orderLineId)->first();

        return new OrderLine(
            id: $row->id,
            idPedido: $row->id_pedido,
            idProducto: $row->id_producto,
            cantidad: $row->cantidad,
            precio: $row->precio,
            nombre: $row->nombre,
            observaciones: $row->observaciones,
            estado: $row->estado
        );
    }
    public function delete(int $id): bool
    {
        $idDelete = DB::table('lineas_pedido')
            ->where('id', $id)
            ->delete();
        return $idDelete > 0;
    }

}
