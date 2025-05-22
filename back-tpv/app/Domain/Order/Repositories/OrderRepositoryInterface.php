<?php

namespace App\Domain\Order\Repositories;
use App\Application\Order\DTO\AddOrderCommand;
use App\Application\Order\DTO\UpdateOrderDinersCommand;
use App\Application\Order\DTO\UpdateOrderStatusCommand;
use App\Domain\Order\Entities\Order;


interface OrderRepositoryInterface
{
    public function exist(int $idPedido): bool;

    public function create(AddOrderCommand $command): Order;

    public function updateDiners(UpdateOrderDinersCommand $order): Order;
    public function updateStatus(UpdateOrderStatusCommand $order): Order;
    public function findByID(UpdateOrderStatusCommand $command): Order;
}