<?php
// UpdateEmployeeHandler.php
namespace App\Application\Payment\Handlers;

use App\Application\Category\DTO\ListCategoryCommand;
use App\Application\Category\UseCases\ListCategoryUseCase;
use App\Application\Payment\DTO\AddPaymentCommand;
use App\Application\Payment\DTO\ListPaymentsOrderCommand;
use App\Application\Payment\UseCases\ListAllPaymentsUseCase;
use App\Application\Payment\DTO\ListAllPaymentsCommand;
use App\Application\Payment\UseCases\ListPaymentsOrderUseCase;
use Illuminate\Support\Collection;

class ListPaymentsOrderCommandHandler
{
    private ListPaymentsOrderUseCase $listPaymentsOrderUseCase;
    public function __construct(ListPaymentsOrderUseCase $listPaymentsOrderUseCase)
    {
        $this->listPaymentsOrderUseCase = $listPaymentsOrderUseCase;
    }

    public function handle(ListPaymentsOrderCommand $command): Collection
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->listPaymentsOrderUseCase->__invoke($command);
    }

}