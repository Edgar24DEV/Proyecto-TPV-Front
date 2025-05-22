<?php

namespace App\Domain\Product\Repositories;

use App\Application\Category\DTO\AddCategoryCommand;
use App\Application\Category\DTO\UpdateActiveCategoryCommand;
use App\Application\Category\DTO\UpdateCategoryCommand;
use App\Domain\Product\Entities\Category;


interface CategoryRepositoryInterface
{
    public function exist(int $id): bool;
    public function existCategory(String $category, int $idCompany): bool;
    public function find(int $idRestaurant): array;
    public function create(AddCategoryCommand $command): Category;
    public function update(UpdateCategoryCommand $command): Category;
    public function updateActive(UpdateActiveCategoryCommand $command): Category;
    public function delete(int $id): bool;
    
}
