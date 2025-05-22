<?php

namespace Controllers\Order;

use App\Application\Order\DTO\AddOrderCommand;
use App\Application\Order\Handlers\AddOrderCommandHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\Order\UseCases\AddOrderUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;


class PostOrderController
{
    use ApiResponseTrait;


    public function __construct(
        private readonly AddOrderUseCase $addOrdersUseCase,
        private readonly AddOrderCommandHandler $addOrderCommandHandler,
    ) {

    }


    public function __invoke(Request $request): JsonResponse
    {
        try {

            $this->validateOrFail($request);
            $command = new AddOrderCommand(
                $request->get("comensales"),
                $request->get("idMesa"),
            );

            $result = $this->addOrderCommandHandler->handle($command);
            $this->validateOfUseCase($result);
            return response()->json($result);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('order')->warning(
                "Fallo al crear el pedido \n" .
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

    private function validateOrFail($request): void
    {
        $request->validate([
            'comensales' => 'required|integer|min:1',
            'idMesa' => 'required|exists:mesas,id',
        ]);
    }

    private function validateOfUseCase($result): void
    {
        if ($result->id < 1) {
            $this->apiError("No se ha podido crear el pedido");
        }
    }
}