<?php
namespace Controllers\Pdf;

use App\Application\Order\DTO\GenerateBillCommand;
use App\Application\Order\UseCases\GenerateBillUseCase;
use App\Application\Order\UseCases\GenerateTicketUseCase;
use App\Domain\Order\Repositories\BillRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GenerateBillController
{
    private BillRepositoryInterface $billRepository;

    public function __construct(
        private readonly GenerateBillUseCase $useCase,
        billRepositoryInterface $billRepository, // Inyectamos el repositorio aquí
    ) {
        $this->billRepository = $billRepository;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $idPedido = $request->get('id_pedido');
        $idRestaurante = $request->get('id_restaurante');
        $idCliente = $request->get('id_cliente');
        $total = $request->get('total');
        $iva = $request->get('iva');
        $tipo = $request->get('tipo');

        // Obtener el pedido con sus líneas desde el repositorio
        $orderData = $this->billRepository->getOrderWithLines($idPedido, $idRestaurante, $idCliente, $total, $tipo);
        // Creamos el comando con la información obtenida
        $command = new GenerateBillCommand(
            $orderData['pedido'],
            $orderData['lineas'],
            $total,
            $iva,
            $orderData['restaurante'],
            $orderData['cliente'],
            $orderData['pago'],
        );

        // Llamamos al caso de uso para generar la factura
        $url = ($this->useCase)($command);


        // Retornamos la URL del ticket generado
        return response()->json(['ticket_url' => $url]);
    }
}
