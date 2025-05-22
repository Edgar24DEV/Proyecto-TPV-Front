<?php

namespace App\Domain\Product\Repositories;
use App\Application\Product\DTO\UpdateProductRestaurantCommand;
use App\Application\Table\DTO\AddTableCommand;
use App\Domain\Product\Entities\Product;
use App\Domain\Table\Entities\Table;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    public function exist(int $id): bool;
    public function find(int $idRestaurant): array;
    public function save(Product $command): Product;
    public function updateProductRestaurant(UpdateProductRestaurantCommand $command): Product;
}
