<?php
// UpdateEmployeeHandler.php
namespace App\Application\Payment\Handlers;

use App\Application\Category\DTO\ListCategoryCommand;
use App\Application\Category\UseCases\ListCategoryUseCase;
use App\Application\Payment\DTO\AddPaymentCommand;
use App\Application\Payment\DTO\ListPaymentsClientCommand;
use App\Application\Payment\DTO\ListPaymentsOrderCommand;
use App\Application\Payment\UseCases\ListAllPaymentsUseCase;
use App\Application\Payment\DTO\ListAllPaymentsCommand;
use App\Application\Payment\UseCases\ListPaymentsClientUseCase;
use App\Application\Payment\UseCases\ListPaymentsOrderUseCase;
use Illuminate\Support\Collection;

class ListPaymentsClientCommandHandler
{
    private ListPaymentsClientUseCase $listPaymentsClientUseCase;
    public function __construct(ListPaymentsClientUseCase $listPaymentsClientUseCase)
    {
        $this->listPaymentsClientUseCase = $listPaymentsClientUseCase;
    }

    public function handle(ListPaymentsClientCommand $command): Collection
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->listPaymentsClientUseCase->__invoke($command);
    }

}