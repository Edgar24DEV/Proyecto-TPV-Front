<?php

// App/Infrastructure/Http/Controllers/OrderController.php

namespace Controllers\Order;

use App\Application\Order\DTO\GetOngoingOrdersCommand;
use App\Application\Order\DTO\GetRestaurantOrdersCommand;
use App\Application\Order\Handlers\GetRestaurantOrdersCommandHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GetRestaurantOrdersController
{
    private GetRestaurantOrdersCommandHandler $getOrderCommandHandler;

    public function __construct(GetRestaurantOrdersCommandHandler $getOrderCommandHandler)
    {
        $this->getOrderCommandHandler = $getOrderCommandHandler;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $idRestaurante = (int) $request->input('id_restaurante');

        try {
            $command = new GetRestaurantOrdersCommand(
                idRestaurante: $idRestaurante
            );

            $order = $this->getOrderCommandHandler->handle($command);

            return response()->json($order);

        } catch (\Exception $e) {
            Log::channel('order')->warning(
                "Fallo al obtener los pedidos del restaurante \n" .
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
