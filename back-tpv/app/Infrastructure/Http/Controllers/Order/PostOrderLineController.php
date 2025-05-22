<?php

namespace Controllers\Order;

use App\Application\Employee\DTO\AddEmployeeCommand;
use App\Application\Employee\Handlers\AddEmployeeCommandHandler;
use App\Application\OrderLine\DTO\AddOrderLineCommand;
use App\Application\OrderLine\Handlers\AddOrderLineCommandHandler;
use App\Application\OrderLine\UseCases\AddOrderLineUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\Employee\UseCases\AddEmployeeUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;


class PostOrderLineController
{
    use ApiResponseTrait;


    public function __construct(
        private readonly AddOrderLineUseCase $addOrderLineUseCase,
        private readonly AddOrderLineCommandHandler $addOrderLineCommandHandler,
    ) {

    }


    public function __invoke(Request $request): JsonResponse
    {
        try {

            $command = new AddOrderLineCommand(
                $request->get("id_pedido"),
                $request->get("id_producto"),
                $request->get("cantidad"),
                $request->get("observaciones"),
                $request->get("estado"),
            );

            $result = $this->addOrderLineCommandHandler->handle($command);
            $this->validateOfUseCase($result);
            return response()->json($result);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('order')->warning(
                "Fallo al crear la linea de pedido \n" .
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
                'error' => 'La validación falló.',
                'messages' => $e->errors()
            ], 422);
        }
    }



    private function validateOfUseCase($result): void
    {
        if ($result->id < 1) {
            $this->apiError("No se ha podido crear la linea pedido");
        }
    }
}