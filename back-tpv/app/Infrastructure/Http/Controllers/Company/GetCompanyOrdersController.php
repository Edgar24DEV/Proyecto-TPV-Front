<?php

// App/Infrastructure/Http/Controllers/OrderController.php

namespace Controllers\Company;

use App\Application\Order\DTO\GetCompanyOrdersCommand;
use App\Application\Order\DTO\GetOngoingOrdersCommand;
use App\Application\Order\DTO\GetRestaurantOrdersCommand;
use App\Application\Order\Handlers\GetCompanyOrdersCommandHandler;
use App\Application\Order\Handlers\GetRestaurantOrdersCommandHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GetCompanyOrdersController
{
    private GetCompanyOrdersCommandHandler $getOrderCommandHandler;

    public function __construct(GetCompanyOrdersCommandHandler $getOrderCommandHandler)
    {
        $this->getOrderCommandHandler = $getOrderCommandHandler;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $idEmpresa = (int) $request->input('id_empresa');

        try {
            $command = new GetCompanyOrdersCommand(
                idEmpresa: $idEmpresa
            );

            $order = $this->getOrderCommandHandler->handle($command);

            return response()->json($order);

        } catch (\Exception $e) {
            Log::channel('company')->error("Error al enviar todos los pedidos de la empresa {$command->getIdEmpresa()} \n" .
            "   Clase: " . __CLASS__ . "\n" .
            "   Mensaje: " . $e->getMessage() . "\n" .
            "   Línea: " . $e->getLine() . "\n" .
            "   Trace:\n" . collect($e->getTrace())
            ->take(3)
            ->map(function ($trace, $i) {
                return "    #$i " . ($trace['file'] ?? '') . ':' . ($trace['line'] ?? '') . ' → ' . ($trace['function'] ?? '');
            })
            ->implode("\n") . "\n");
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage()
            ], 404);
        }
    }
}
