<?php

namespace App\Infrastructure\Repositories;

use App\Application\Product\DTO\AddProductCommand;
use App\Application\Product\DTO\AddProductRestaurantCommand;
use App\Application\Product\DTO\GetProductRestaurantCommand;
use App\Application\Product\DTO\UpdateDeactivateProductCommand;
use App\Application\Product\DTO\UpdateProductCommand;
use App\Application\Product\DTO\UpdateProductRestaurantCommand;
use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Domain\Product\Entities\Product;
use Illuminate\Support\Facades\DB;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function exist(int $idProducto): bool
    {
        $results = DB::table('productos')
            ->where('id', $idProducto)
            ->whereNull('deleted_at')
            ->exists();

        return $results;
    }

    public function findById(int $idProducto): Product
    {
        $row = DB::table('productos')
            ->where('id', $idProducto)
            ->whereNull('deleted_at')
            ->first();

        return new Product(
            id: $row->id,
            nombre: $row->nombre,
            precio: $row->precio,
            imagen: $row->imagen,
            activo: $row->activo,
            iva: $row->iva,
            idCategoria: $row->id_categoria,
            idEmpresa: $row->id_empresa,
        );
    }


    public function delete(int $id): bool
    {
        $delete = DB::table('productos')
            ->where('id', $id)
            ->update(['deleted_at' => now()]);

        return $delete > 0;
    }

    public function notEqual(string $nombre, int $idEmpresa): bool
    {
        $results = DB::table('productos')
            ->where('nombre', 'like', $nombre)
            ->where('id_empresa', $idEmpresa)
            ->whereNull('deleted_at')
            ->exists();

        return $results;
    }

    public function find(int $restaurant_id): array
    {
        $results = DB::table('productos')
            ->join('restaurante_producto', 'productos.id', '=', 'restaurante_producto.id_producto')
            ->join('categorias', 'categorias.id', '=', 'productos.id_categoria')
            ->where('restaurante_producto.id_restaurante', $restaurant_id)
            ->where('restaurante_producto.activo', '=', true)
            ->whereNull('productos.deleted_at')
            ->whereNull('categorias.deleted_at')
            ->select('productos.*')
            ->get();

        return $results->map(function ($row) {
            return new Product(
                id: $row->id,
                nombre: $row->nombre,
                precio: $row->precio,
                imagen: $row->imagen,
                activo: $row->activo,
                iva: $row->iva,
                idCategoria: $row->id_categoria,
                idEmpresa: $row->id_empresa,
            );
        })->toArray();
    }

    public function findAll(int $restaurant_id): array
    {
        $results = DB::table('productos')
            ->join('restaurante_producto', 'productos.id', '=', 'restaurante_producto.id_producto')
            ->join('categorias', 'categorias.id', '=', 'productos.id_categoria')
            ->where('restaurante_producto.id_restaurante', $restaurant_id)
            ->where('restaurante_producto.activo', '=', true)
            ->where('productos.activo', '=', true)
            ->whereNull('productos.deleted_at')
            ->whereNull('categorias.deleted_at')
            ->select('productos.*')
            ->get();

        return $results->map(function ($row) {
            return new Product(
                id: $row->id,
                nombre: $row->nombre,
                precio: $row->precio,
                imagen: $row->imagen,
                activo: $row->activo,
                iva: $row->iva,
                idCategoria: $row->id_categoria,
                idEmpresa: $row->id_empresa,
            );
        })->toArray();
    }

    public function findByCompany(int $idEmpresa): array
    {
        $results = DB::table('productos')
            ->where('id_empresa', $idEmpresa)
            ->whereNull('deleted_at')
            ->get();

        return $results->map(function ($row) {
            return new Product(
                id: $row->id,
                nombre: $row->nombre,
                precio: $row->precio,
                imagen: $row->imagen,
                activo: $row->activo,
                iva: $row->iva,
                idCategoria: $row->id_categoria,
                idEmpresa: $row->id_empresa,
            );
        })->toArray();
    }

    public function save(Product $command): Product
    {
        $imagen = $command->getImagen();

        // Si te llega un archivo (por ejemplo, en base64 o un UploadFile) lo manejas aquí
        if ($imagen instanceof \Illuminate\Http\UploadedFile) {
            $path = $imagen->store('', 'public');
            $imagen = $path;
        }

        $id = DB::table('productos')->insertGetId([
            'nombre' => $command->getNombre(),
            'precio' => $command->getPrecio(),
            'imagen' => $imagen, // Aquí ya es una ruta como 'products/imagen.jpg'
            'activo' => $command->getActivo() ? 1 : 0,
            'iva' => $command->getIva() ?? 10.0,
            'id_categoria' => $command->getIdCategoria(),
            'id_empresa' => $command->getIdEmpresa(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $results = DB::table('productos')->where('id', '=', $id)->first();

        return new Product(
            id: $id,
            nombre: $results->nombre,
            precio: $results->precio,
            imagen: $results->imagen,
            activo: $results->activo ?? true,
            iva: $results->iva ?? 10.0,
            idCategoria: $results->id_categoria,
            idEmpresa: $results->id_empresa,
        );
    }


    public function updateProductRestaurant(UpdateProductRestaurantCommand $command): Product
    {
        DB::table('restaurante_producto')
            ->where('id_producto', $command->getIdProducto())
            ->where('id_restaurante', $command->getIdRestaurante())
            ->update([
                'activo' => $command->getActivo() ? 1 : 0,
                'updated_at' => now(),
            ]);

        $results = DB::table('productos')
            ->where('id', '=', $command->getIdProducto())
            ->whereNull('deleted_at')
            ->first();

        return new Product(
            id: $results->id,
            nombre: $results->nombre,
            precio: $results->precio,
            imagen: $results->imagen,
            activo: $results->activo,
            iva: $results->iva ?? 10.0,
            idCategoria: $results->id_categoria,
            idEmpresa: $results->id_empresa,
        );
    }

    public function getProductRestaurant(GetProductRestaurantCommand $command): UpdateProductRestaurantCommand
    {
        $results = DB::table('restaurante_producto')
            ->where('id_producto', $command->getIdProducto())
            ->where('id_restaurante', $command->getIdRestaurante())
            ->first();

        return new UpdateProductRestaurantCommand(
            activo: $results->activo,
            idProducto: $results->id_producto,
            idRestaurante: $results->id_restaurante,
        );
    }

    public function updateProductDeactivate(UpdateDeactivateProductCommand $command): Product
    {
        DB::table('productos')
            ->where('id', $command->getIdProducto())
            ->whereNull('deleted_at')
            ->update([
                'activo' => $command->getActivo() ? 1 : 0,
                'updated_at' => now(),
            ]);

        $results = DB::table('productos')
            ->where('id', '=', $command->getIdProducto())
            ->whereNull('deleted_at')
            ->first();

        return new Product(
            id: $results->id,
            nombre: $results->nombre,
            precio: $results->precio,
            imagen: $results->imagen,
            activo: $results->activo,
            iva: $results->iva ?? 10.0,
            idCategoria: $results->id_categoria,
            idEmpresa: $results->id_empresa,
        );
    }

    public function update(UpdateProductCommand $product): Product
    {
        DB::table('productos')
            ->where('id', $product->getId())
            ->whereNull('deleted_at')
            ->update([
                'nombre' => $product->getNombre(),
                'precio' => $product->getPrecio(),
                'imagen' => $product->getImagen(),
                'activo' => $product->getActivo() ? 1 : 0,
                'id_categoria' => $product->getCategoria(),
                'iva' => $product->getIva() ?? 10,
                'updated_at' => now(),
            ]);

        $results = DB::table('productos')
            ->where('id', '=', $product->getId())
            ->whereNull('deleted_at')
            ->first();

        return new Product(
            id: $results->id,
            nombre: $product->getNombre(),
            precio: $product->getPrecio(),
            imagen: $product->getImagen(),
            activo: $product->getActivo() ? 1 : 0,
            iva: $product->getIva() ?? 10.0,
            idCategoria: $product->getCategoria(),
            idEmpresa: $results->id_empresa,
        );
    }
    public function findByProductAndRestaurant(int $productId, int $restaurantId): bool
    {
        $row = DB::table('restaurante_producto')
            ->where('id_restaurante', $restaurantId)
            ->where('id_producto', $productId)
            ->exists();

        return $row;
    }
}
