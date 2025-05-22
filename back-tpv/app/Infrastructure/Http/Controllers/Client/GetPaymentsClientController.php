<?php

namespace Controllers\Client;

use App\Application\Employee\DTO\FindByIdEmployeeCommand;
use App\Application\Employee\Handlers\FindByIdEmployeeCommandHandler;
use App\Application\Employee\UseCases\FindByIdEmployeeUseCase;
use App\Application\Payment\DTO\ListPaymentsClientCommand;
use App\Application\Payment\DTO\ListPaymentsOrderCommand;
use App\Application\Payment\Handlers\ListAllPaymentsCommandHandler;
use App\Application\Payment\Handlers\ListPaymentsClientCommandHandler;
use App\Application\Payment\UseCases\ListAllPaymentsUseCase;
use App\Application\Payment\DTO\ListAllPaymentsCommand;
use App\Application\Payment\UseCases\ListPaymentsClientUseCase;
use Illuminate\Http\Request;
use App\Application\Employee\UseCases\ListEmployeesRestaurantUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class GetPaymentsClientController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly ListPaymentsClientUseCase $ListPaymentsClientUseCase,
        private readonly ListPaymentsClientCommandHandler $ListPaymentsClientCommandHandler,
    ) {

    }

    public function __invoke(Request $request)
    {

        $this->isNanId($request->input("id_cliente"));

        try {

            $idCliente = new ListPaymentsClientCommand(
                $request->input("id_cliente"),
            );

            $result = $this->ListPaymentsClientCommandHandler->handle($idCliente);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::channel('company')->error(
                "No se pudo encontrar los pagos del cliente \n" .
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
