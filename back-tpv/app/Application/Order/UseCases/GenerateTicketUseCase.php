<?php
namespace App\Application\Order\UseCases;

use App\Application\Order\DTO\GenerateTicketCommand;
use App\Domain\Order\Services\TicketGeneratorServiceInterface;

class GenerateTicketUseCase
{
    private TicketGeneratorServiceInterface $ticketGeneratorService;

    public function __construct(TicketGeneratorServiceInterface $ticketGeneratorService)
    {
        $this->ticketGeneratorService = $ticketGeneratorService;
    }

    public function __invoke(GenerateTicketCommand $command): string
    {
        // Usamos el servicio de generación de tickets con la información recibida
        return $this->ticketGeneratorService->generate(
            $command->order,
            $command->lines,
            $command->total,
            $command->iva,
            $command->restaurant,
        );
    }
}
