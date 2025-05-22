<?php
namespace App\Application\Order\UseCases;

use App\Application\Order\DTO\GenerateBillCommand;
use App\Application\Order\DTO\GenerateTicketCommand;
use App\Domain\Order\Services\BillGeneratorServiceInterface;
use App\Domain\Order\Services\TicketGeneratorServiceInterface;
use App\Infrastructure\Pdf\DompdfBillGeneratorService;

class GenerateBillUseCase
{
    private BillGeneratorServiceInterface $billGeneratorService;

    public function __construct(DompdfBillGeneratorService $billGeneratorService)
    {
        $this->billGeneratorService = $billGeneratorService;
    }

    public function __invoke(GenerateBillCommand $command): string
    {
        // Usamos el servicio de generación de tickets con la información recibida
        return $this->billGeneratorService->generate(
            $command->order,
            $command->lines,
            $command->total,
            $command->iva,
            $command->restaurant,
            $command->client,
            $command->payment,
        );
    }
}
