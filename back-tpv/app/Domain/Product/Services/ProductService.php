<?php
namespace App\Domain\Product\Services;
use App\Domain\Product\Entities\Product;
use Illuminate\Http\Request;
class ProductService
{

    public function showProductInfo($categories)
    {

        return collect($categories)->map(function ($row) {
            return new Product(
                id: $row->id,
                nombre: $row->nombre,
                precio: $row->precio,
                imagen: $row->imagen,
                activo: $row->activo,
                iva: $row->iva,
                idCategoria: $row->idCategoria,
                idEmpresa: $row->idEmpresa,
            );
        });
    }
    public function showProductInfoSimple($product)
    {
        return new Product(
            id: $product->id,
                nombre: $product->nombre,
                precio: $product->precio,
                imagen: $product->imagen,
                activo: $product->activo,
                iva: $product->iva,
                idCategoria: $product->idCategoria,
                idEmpresa: $product->idEmpresa,
        );
    }


}