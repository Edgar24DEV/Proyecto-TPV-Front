<?php

namespace Controllers\Payment;

use App\Application\Order\DTO\AddOrderCommand;
use App\Application\Order\Handlers\AddOrderCommandHandler;
use App\Application\Payment\DTO\AddPaymentCommand;
use App\Application\Payment\Handlers\AddPaymentCommandHandler;
use App\Application\Payment\UseCases\AddPaymentUseCase;
use App\Application\Product\DTO\AddProductCommand;
use App\Application\Product\Handlers\AddProductCommandHandler;
use App\Application\Product\UseCases\AddProductUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\Order\UseCases\AddOrderUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;


class PostPaymentController
{
    use ApiResponseTrait;


    public function __construct(
        private readonly AddPaymentUseCase $addPaymentUseCase,
        private readonly AddPaymentCommandHandler $addPaymentCommandHandler,
    ) {

    }


    public function __invoke(Request $request): JsonResponse
    {
        try {

            $command = new AddPaymentCommand(
                $request->get("tipo"),
                (float) $request->get("cantidad"),
                $request->get("fecha"),
                $request->get("id_pedido"),
                $request->get("id_cliente"),
                $request->get("razon_social"),
                $request->get("cif"),
                $request->get("n_factura"),
                $request->get("correo"),
                $request->get("direccion_fiscal"),
            );

            $result = $this->addPaymentCommandHandler->handle($command);

            return response()->json($result->toArray());


        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('order')->warning(
                "La validación falló al crear el pago del pedido{$request->input('id_pedido')}\n" .
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
                "Error en algún campo al crear el pago del pedido{$request->input('id_pedido')}\n" .
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
                "Error al crear el pago del pedido {$request->input('id_pedido')}\n" .
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

}