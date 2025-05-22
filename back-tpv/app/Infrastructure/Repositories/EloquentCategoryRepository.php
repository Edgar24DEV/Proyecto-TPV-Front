<?php

namespace App\Infrastructure\Repositories;

use App\Application\Category\DTO\AddCategoryCommand;
use App\Application\Category\DTO\UpdateActiveCategoryCommand;
use App\Application\Category\DTO\UpdateCategoryCommand;
use App\Domain\Product\Entities\Category;
use App\Domain\Product\Repositories\CategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Termwind\ValueObjects\Node;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    public function exist(int $id): bool
    {
        $results = DB::table('categorias')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->exists();

        return $results;
    }
    public function existCategory(string $category, int $idCompany): bool
    {
        $results = DB::table('categorias')
            ->where('categoria', $category)
            ->where('id_empresa', $idCompany)
            ->whereNull('deleted_at')
            ->exists();

        return $results;
    }
    public function find(int $restaurant_id): array
    {
        /*
        return Table::whereHas('restaurantes', function ($query) use ($restaurant_id) {
            $query->where('id_restaurante', $restaurant_id);
        })->get()->toArray();
        */

        $results = DB::table('categorias')
            ->join('productos', 'productos.id_categoria', '=', 'categorias.id')
            ->join('restaurante_producto', 'restaurante_producto.id_producto', '=', 'productos.id')
            ->where('restaurante_producto.id_restaurante', $restaurant_id)
            ->where('categorias.activo', '=', 1)
            ->whereNull('categorias.deleted_at')
            ->select('categorias.*')
            ->get()
            ->groupBy('id');


        return $results->map(function ($group) {
            $item = $group->first(); // obtén el primer elemento del grupo (todos son de la misma categoría)

            return new Category(
                id: $item->id,
                categoria: $item->categoria,
                activo: $item->activo,
                idEmpresa: $item->id_empresa,
            );
        })->values()->toArray();
    }

    public function findByCompany(int $idCompany): array
    {
        /*
        return Table::whereHas('restaurantes', function ($query) use ($restaurant_id) {
            $query->where('id_restaurante', $restaurant_id);
        })->get()->toArray();
        */

        $results = DB::table('categorias')
            ->where('id_empresa', $idCompany)
            ->whereNull('deleted_at')
            ->get();



        return $results->map(function ($item) {

            return new Category(
                id: $item->id,
                categoria: $item->categoria,
                activo: $item->activo,
                idEmpresa: $item->id_empresa,
            );
        })->values()->toArray();
    }

    public function getCategory(int $id): Category
    {
        /*
        return Table::whereHas('restaurantes', function ($query) use ($restaurant_id) {
            $query->where('id_restaurante', $restaurant_id);
        })->get()->toArray();
        */

        $results = DB::table('categorias')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->first();


        return new Category(
            id: $results->id,
            categoria: $results->categoria,
            activo: $results->activo,
            idEmpresa: $results->id_empresa,
        );

    }

    public function create(AddCategoryCommand $command): Category
    {
        $id = DB::table('categorias')->insertGetId([
            'categoria' => $command->getCategoria(),
            'activo' => true,
            'id_empresa' => $command->getIdEmpresa(),
            'created_at' => Now(),
            'updated_at' => Now(),
        ]);        // Retorna una nueva instancia con el ID generado
        return new Category(
            id: $id,
            categoria: $command->getCategoria(),
            activo: true,
            idEmpresa: $command->getIdEmpresa(),
        );
    }

    public function update(UpdateCategoryCommand $table): Category
    {
        $tableId = $table->getId();
        $id = DB::table('categorias')
            ->where('id', $tableId)
            ->update([
                'categoria' => $table->getCategoria(),
                'activo' => $table->getActivo() ? 1 : 0,
                'updated_at' => Now(),
            ]);


        $products = DB::table('productos')
            ->where('id_categoria', $tableId)
            ->update([
                'activo' => $table->getActivo() ? 1 : 0,
                'updated_at' => Now(),
            ]);


        // Retorna una nueva instancia con el ID generado
        return new Category(
            id: $id,
            categoria: $table->getCategoria(),
            activo: $table->getActivo(),
            idEmpresa: $table->getIdEmpresa(),
        );
    }
    public function updateActive(UpdateActiveCategoryCommand $command): Category
    {
        $id = DB::table('categorias')
            ->where('id', $command->getId())
            ->update([
                'activo' => $command->getActivo() ? 1 : 0,
                'updated_at' => Now(),
            ]);

        // Desactiva los productos relacionados con la categoría
        $productos = DB::table('productos')
            ->where('id_categoria', $command->getId())
            ->get();

        // Desactiva todos los productos relacionados
        DB::table('productos')
            ->where('id_categoria', $command->getId())
            ->update([
                'activo' => $command->getActivo(),
                'updated_at' => Now(),
            ]);

        // Desactiva la relación en 'restaurante_producto' para los productos desactivados
        foreach ($productos as $producto) {
            DB::table('restaurante_producto')
                ->where('id_producto', $producto->id)
                ->update([
                    'activo' => $command->getActivo(),
                    'updated_at' => Now(),
                ]);
        }
        return new Category(
            id: $id,
            categoria: $command->getCategoria(),
            activo: $command->getActivo(),
            idEmpresa: $command->getIdEmpresa(),
        );
    }
    public function delete(int $id): bool
    {
        $delete = DB::table('categorias')
            ->where('id', $id)
            ->update(['deleted_at' => now()]);

        return $delete > 0;
    }
}
