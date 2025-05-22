<?php

namespace App\Application\Payment\Handlers;

use App\Application\Payment\DTO\AddPaymentCommand;
use App\Application\Payment\UseCases\AddPaymentUseCase;
use App\Domain\Order\Entities\Payment;

class AddPaymentCommandHandler
{
    private AddPaymentUseCase $addPaymentUseCase;

    public function __construct(AddPaymentUseCase $addPaymentUseCase)
    {
        $this->addPaymentUseCase = $addPaymentUseCase;
    }

    public function handle(AddPaymentCommand $command): Payment
    {
        return $this->addPaymentUseCase->__invoke($command);
    }
}