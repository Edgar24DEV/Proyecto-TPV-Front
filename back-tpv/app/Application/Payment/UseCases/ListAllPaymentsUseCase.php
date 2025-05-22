<?php
namespace App\Application\Payment\UseCases;

use App\Application\Category\DTO\ListCategoryCommand;

use App\Application\Payment\DTO\AddPaymentCommand;
use App\Application\Payment\DTO\ListAllPaymentsCommand;
use App\Domain\Order\Services\PaymentService;
use App\Domain\Product\Services\CategoryService;
use App\Infrastructure\Repositories\EloquentCategoryRepository;
use App\Infrastructure\Repositories\EloquentPaymentRepository;
use App\Infrastructure\Repositories\EloquentTableRepository;
use App\Infrastructure\Repositories\EloquentRestaurantRepository;
use Illuminate\Support\Collection;
use function PHPUnit\Framework\isNan;

final class ListAllPaymentsUseCase
{



    public function __construct(
        private readonly EloquentPaymentRepository $paymentRepository,
        private readonly EloquentRestaurantRepository $restaurantRepository,
        private readonly PaymentService $paymentService,
    ) {
    }
    public function __invoke(ListAllPaymentsCommand $command): Collection
    {
        $idRestaurant = $command->getIdRestaurant();
        $this->validateOrFail($idRestaurant);
        $payments = $this->paymentRepository->findAll($idRestaurant);
        $payments = $this->paymentService->showPaymentInfo($payments);

        return $payments;
    }


    private function validateOrFail(int $idRestaurant): void
    {
        if ($idRestaurant <= 0) {
            throw new \InvalidArgumentException("ID inválido");
        }

        if (!$this->restaurantRepository->exist($idRestaurant)) {
            throw new \InvalidArgumentException("ID No existe");
        }

    }

}