<?php
namespace App\Domain\Product\Services;
use App\Domain\Product\Entities\Category;
use Illuminate\Http\Request;
class CategoryService
{

    public function showCategoryInfo($categories)
    {

        return collect($categories)->map(function ($row) {
            return new Category(
                id: $row->id,
                categoria: $row->categoria,
                activo: $row->activo,
                idEmpresa: $row->idEmpresa,
            );
        });
    }
    public function showCategoryInfoSimple($category)
    {
        return new Category(
            id: $category->id,
            categoria: $category->categoria,
            activo: $category->activo,
            idEmpresa: $category->idEmpresa,
        );
    }


}