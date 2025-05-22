<?php
// UpdateEmployeeHandler.php
namespace App\Application\Payment\Handlers;

use App\Application\Category\DTO\ListCategoryCommand;
use App\Application\Category\UseCases\ListCategoryUseCase;
use App\Application\Payment\DTO\AddPaymentCommand;
use App\Application\Payment\UseCases\ListAllPaymentsUseCase;
use App\Application\Payment\DTO\ListAllPaymentsCommand;
use Illuminate\Support\Collection;

class ListAllPaymentsCommandHandler
{
    private ListAllPaymentsUseCase $listAllPaymentsUseCase;
    public function __construct(ListAllPaymentsUseCase $listAllPaymentsUseCase)
    {
        $this->listAllPaymentsUseCase = $listAllPaymentsUseCase;
    }

    public function handle(ListAllPaymentsCommand $command): Collection
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->listAllPaymentsUseCase->__invoke($command);
    }

}