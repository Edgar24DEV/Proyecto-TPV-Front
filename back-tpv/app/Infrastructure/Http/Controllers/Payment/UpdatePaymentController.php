<?php

namespace Controllers\Payment;

use App\Application\Order\DTO\UpdateOrderCommand;
use App\Application\Order\Handlers\UpdateOrderCommandHandler;
use App\Application\Payment\DTO\UpdatePaymentCommand;
use App\Application\Payment\Handlers\UpdatePaymentCommandHandler;
use App\Application\Payment\UseCases\UpdatePaymentUseCase;
use App\Application\Product\DTO\UpdateProductCommand;
use App\Application\Product\Handlers\UpdateProductCommandHandler;
use App\Application\Product\UseCases\UpdateProductUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\Order\UseCases\UpdateOrderUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;


class UpdatePaymentController
{
    use ApiResponseTrait;


    public function __construct(
        private readonly UpdatePaymentUseCase $updatePaymentUseCase,
        private readonly UpdatePaymentCommandHandler $updatePaymentCommandHandler,
    ) {}


    public function __invoke(Request $request): JsonResponse
    {
        try {
            $this->validateOrFail($request);
            $command = new UpdatePaymentCommand(
                idPago: $request->get("id"),
                idCliente: $request->get("idCliente"),
                razonSocial: $request->get('razonSocial'),
                CIF: $request->get('CIF'),
                correo: $request->get('correo'),
                direccionFiscal: $request->get('direccionFiscal')
            );

            $result = $this->updatePaymentCommandHandler->handle($command);

            return response()->json($result->toArray());
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('order')->warning(
                "La validación falló al actualizar el pago {$request->input('id')}\n" .
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
        } catch (\InvalidArgumentException $e) {
            Log::channel('order')->warning(
                "Error en algún campo al actualizar el pago {$request->input('id')}\n" .
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
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            Log::channel('order')->error(
                "Error al actualizar el pago {$request->input('id')}\n" .
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
                'error' => $e->getMessage()
            ], 500);
        }
    }
    private function validateOrFail(Request $request): void
    {
        $request->validate([
            "id" => "required|integer|exists:pagos,id",
        ]);
    }
}
