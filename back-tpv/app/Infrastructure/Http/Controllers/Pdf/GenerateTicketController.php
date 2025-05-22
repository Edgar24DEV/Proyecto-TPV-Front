<?php
namespace Controllers\Pdf;

use App\Application\Order\DTO\GenerateTicketCommand;
use App\Application\Order\UseCases\GenerateTicketUseCase;
use App\Domain\Order\Repositories\TicketRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GenerateTicketController
{
    private TicketRepositoryInterface $ticketRepository;

    public function __construct(
        private readonly GenerateTicketUseCase $useCase,
        TicketRepositoryInterface $ticketRepository // Inyectamos el repositorio aquí
    ) {
        $this->ticketRepository = $ticketRepository;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $idPedido = $request->get('id_pedido');
        $idRestaurante = $request->get('id_restaurante');
        $total = $request->get('total');
        $iva = $request->get('iva');

        // Obtener el pedido con sus líneas desde el repositorio
        $orderData = $this->ticketRepository->getOrderWithLines($idPedido, $idRestaurante);
        // Creamos el comando con la información obtenida
        $command = new GenerateTicketCommand(
            $orderData['pedido'],
            $orderData['lineas'],
            $total,
            $iva,
            $orderData['restaurante'],
        );

        // Llamamos al caso de uso para generar el ticket
        $url = ($this->useCase)($command);


        // Retornamos la URL del ticket generado
        return response()->json(['ticket_url' => $url]);
    }
}
