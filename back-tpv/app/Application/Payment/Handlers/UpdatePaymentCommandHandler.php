<?php
// UpdateEmployeeHandler.php
namespace App\Application\Payment\Handlers;

use App\Application\Payment\DTO\UpdatePaymentCommand;
use App\Application\Payment\UseCases\UpdatePaymentUseCase;

class UpdatePaymentCommandHandler
{
    private UpdatePaymentUseCase $updatePaymentUseCase;
    public function __construct(UpdatePaymentUseCase $updatePaymentUseCase)
    {
        $this->updatePaymentUseCase = $updatePaymentUseCase;
    }

    public function handle(UpdatePaymentCommand $command)
    {

        return $this->updatePaymentUseCase->__invoke($command);
    }

}