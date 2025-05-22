<?php

namespace Controllers\Order;

use App\Application\Employee\DTO\FindByIdEmployeeCommand;
use App\Application\Employee\Handlers\FindByIdEmployeeCommandHandler;
use App\Application\Employee\UseCases\FindByIdEmployeeUseCase;
use App\Application\Payment\DTO\ListPaymentsOrderCommand;
use App\Application\Payment\Handlers\ListAllPaymentsCommandHandler;
use App\Application\Payment\Handlers\ListPaymentsOrderCommandHandler;
use App\Application\Payment\UseCases\ListAllPaymentsUseCase;
use App\Application\Payment\DTO\ListAllPaymentsCommand;
use App\Application\Payment\UseCases\ListPaymentsOrderUseCase;
use Illuminate\Http\Request;
use App\Application\Employee\UseCases\ListEmployeesRestaurantUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class GetPaymentsOrderController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly ListPaymentsOrderUseCase $listPaymentsOrderUseCase,
        private readonly ListPaymentsOrderCommandHandler $listPaymentsOrderCommandHandler,
    ) {

    }

    public function __invoke(Request $request)
    {

        $this->isNanId($request->input("id_pedido"));

        try {

            $idPedido = new ListPaymentsOrderCommand(
                $request->input("id_pedido"),
            );

            $result = $this->listPaymentsOrderCommandHandler->handle($idPedido);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::channel('order')->warning(
                "Fallo al obtener los pagos del pedido \n" .
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
            return $this->apiError("" . $e->getMessage());
        }
    }

    private function isNanId($id)
    {
        if ($id === null || !is_numeric($id)) {
            throw new \InvalidArgumentException("El ID no es un número");
        }
        /*
        if (!is_numeric($id)) {
            throw new \InvalidArgumentException("El ID no es un número");
        }*/
    }
}
