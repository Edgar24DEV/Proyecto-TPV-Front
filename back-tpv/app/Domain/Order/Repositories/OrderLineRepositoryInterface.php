<?php

namespace App\Domain\Order\Repositories;
use App\Application\OrderLine\DTO\AddOrderLineCommand;
use App\Application\OrderLine\DTO\UpdateOrderLineCommand;
use \App\Domain\Order\Entities\OrderLine;
use App\Domain\Order\Entities;
use App\Domain\Product\Entities\Product;


interface OrderLineRepositoryInterface
{
    public function exist(int $orderLine): bool;
    public function find(int $orderLine): array;

    public function create(AddOrderLineCommand $command): OrderLine;
    public function findProduct(int $idProduct): Product;
    public function update(UpdateOrderLineCommand $orderLine): OrderLine;
    public function delete(int $id): bool;
}
