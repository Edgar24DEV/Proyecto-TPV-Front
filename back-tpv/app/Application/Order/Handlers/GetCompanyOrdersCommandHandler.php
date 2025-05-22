<?php
// UpdateOrderHandler.php
namespace App\Application\Order\Handlers;

use App\Application\Order\DTO\AddOrderCommand;
use App\Application\Order\DTO\GetCompanyOrdersCommand;
use App\Application\Order\DTO\GetOngoingOrdersCommand;
use App\Application\Order\DTO\GetOrderCommand;
use App\Application\Order\DTO\GetRestaurantOrdersCommand;
use App\Application\Order\DTO\UpdateOrderCommand;
use App\Application\Order\DTO\UpdateOrderDTO;
use App\Application\Order\UseCases\AddOrderUseCase;
use App\Application\Order\UseCases\GetCompanyOrdersUseCase;
use App\Application\Order\UseCases\GetOngoingOrdersUseCase;
use App\Application\Order\UseCases\GetOrderUseCase;
use App\Application\Order\UseCases\GetRestaurantOrdersUseCase;
use App\Domain\Order\Entities\Order;
use App\Domain\Audit\Services\AuditService;
use Illuminate\Support\Collection;

class GetCompanyOrdersCommandHandler
{
    private GetCompanyOrdersUseCase $getOrderUseCase;


    public function __construct(GetCompanyOrdersUseCase $getOrderUseCase)
    {
        $this->getOrderUseCase = $getOrderUseCase;
    }

    public function handle(GetCompanyOrdersCommand $command): Collection
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->getOrderUseCase->__invoke($command);
    }

}