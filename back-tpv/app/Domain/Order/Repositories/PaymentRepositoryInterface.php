<?php

namespace App\Domain\Order\Repositories;
use App\Application\Payment\DTO\AddPaymentCommand;
use App\Application\Payment\DTO\UpdatePaymentCommand;
use App\Domain\Order\Entities\Order;
use App\Domain\Order\Entities\Payment;


interface PaymentRepositoryInterface
{
    public function exist(int $id): bool;

    public function save(AddPaymentCommand $command): Payment;

    public function update(UpdatePaymentCommand $command): Payment;
}