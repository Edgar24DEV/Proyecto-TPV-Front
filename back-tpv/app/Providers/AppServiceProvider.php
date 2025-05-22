<?php

namespace App\Providers;

use App\Domain\Order\Repositories\BillRepositoryInterface;
use App\Domain\Order\Repositories\TicketRepositoryInterface;
use App\Domain\Order\Services\BillGeneratorServiceInterface;
use App\Domain\Order\Services\TicketGeneratorServiceInterface;
use App\Infrastructure\Pdf\DompdfTicketGeneratorService;
use App\Infrastructure\Repositories\EloquentBillRepository;
use App\Infrastructure\Repositories\EloquentTicketRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TicketGeneratorServiceInterface::class, DompdfTicketGeneratorService::class);
        $this->app->bind(TicketRepositoryInterface::class, EloquentTicketRepository::class); // 🔧 Este es el nuevo binding
        $this->app->bind(BillGeneratorServiceInterface::class, DompdfTicketGeneratorService::class);
        $this->app->bind(BillRepositoryInterface::class, EloquentBillRepository::class); // 🔧 Este es el nuevo binding
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
