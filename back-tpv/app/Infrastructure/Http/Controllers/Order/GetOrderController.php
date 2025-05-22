<?php

// App/Infrastructure/Http/Controllers/OrderController.php

namespace Controllers\Order;

use App\Application\Order\DTO\GetOrderCommand;
use App\Application\Order\Handlers\GetOrderCommandHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GetOrderController
{
    private GetOrderCommandHandler $getOrderCommandHandler;

    public function __construct(GetOrderCommandHandler $getOrderCommandHandler)
    {
        $this->getOrderCommandHandler = $getOrderCommandHandler;
    }

    public function getOrder(Request $request): JsonResponse
    {
        $idMesa = (int) $request->input('id_mesa');

        try {
            $command = new GetOrderCommand(
                idMesa: $idMesa
            );

            $order = $this->getOrderCommandHandler->handle($command);

            return response()->json($order);

        } catch (\Exception $e) {
            Log::channel('order')->warning(
                "Fallo al obtener el pedido \n" .
                "   Clase: " . __CLASS__ . "\n" .
                "   Mensaje: " . $e->getMessage() . "\n" .
                "   Línea: " . $e->getLine() . "\n" .
                "   Trace:\n" . collect($e->getTrace())
                    ->take(3)
                    ->map(function ($trace, $i) {
                        return "    #$i " . ($trace['file'] ?? '') . ':' . ($trace['line'] ?? '') . ' → ' . ($trace['function'] ?? '');
                    })
                    ->implode("\n") . "\n"
            );
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage()
            ], 404);
        }
    }
}
