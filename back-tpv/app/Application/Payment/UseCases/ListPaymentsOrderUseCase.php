<?php
namespace App\Application\Payment\UseCases;

use App\Application\Category\DTO\ListCategoryCommand;

use App\Application\Payment\DTO\AddPaymentCommand;
use App\Application\Payment\DTO\ListAllPaymentsCommand;
use App\Application\Payment\DTO\ListPaymentsOrderCommand;
use App\Domain\Order\Services\PaymentService;
use App\Domain\Product\Services\CategoryService;
use App\Infrastructure\Repositories\EloquentCategoryRepository;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use App\Infrastructure\Repositories\EloquentPaymentRepository;
use App\Infrastructure\Repositories\EloquentTableRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use Illuminate\Support\Collection;
use function PHPUnit\Framework\isNan;

final class ListPaymentsOrderUseCase
{



    public function __construct(
        private readonly EloquentPaymentRepository $paymentRepository,
        private readonly EloquentOrderRepository $orderRepository,
        private readonly PaymentService $paymentService,
    ) {
    }
    public function __invoke(ListPaymentsOrderCommand $command): Collection
    {
        $idPedido = $command->getIdPedido();
        $this->validateOrFail($idPedido);
        $payments = $this->paymentRepository->findByOrder($idPedido);
        $payments = $this->paymentService->showPaymentInfo($payments);

        return $payments;
    }


    private function validateOrFail(int $idPedido): void
    {
        if ($idPedido <= 0) {
            throw new \InvalidArgumentException("ID inválido");
        }

        if (!$this->orderRepository->exist($idPedido)) {
            throw new \InvalidArgumentException("ID No existe");
        }

    }

}