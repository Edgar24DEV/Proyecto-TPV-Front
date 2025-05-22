<?php


namespace Controllers\Order;

use App\Application\OrderLine\DTO\ListOrderLineTableCommand;
use App\Application\OrderLine\Handlers\ListOrderLineTableCommandHandler;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GetListOrderLineTableController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly ListOrderLineTableCommandHandler $listOrderLineTableCommandHandler
    ) {
    }

    public function __invoke(Request $request)
    {
        try {
            // Obtener el valor de id_pedido
            $idOrderLine = $request->input("id_pedido");

            // Verifica si el id_pedido es nulo o no numérico
            if ($idOrderLine !== null && !is_numeric($idOrderLine)) {
                throw new \InvalidArgumentException("El ID no es un número");
            }

            // Convierte el valor a entero
            $idOrderLine = (int) $idOrderLine;

            // Crea el comando con el ID procesado
            $command = new ListOrderLineTableCommand($idOrderLine);

            // Llamar al handler para manejar el comando
            $result = $this->listOrderLineTableCommandHandler->handle($command);

            // Retornar la respuesta como JSON
            return response()->json($result);
        } catch (\Exception $e) {
            Log::channel('order')->warning(
                "Fallo al listar las lineas del pedido con ID: {$request->input("id_pedido")} \n" .
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
            // Si ocurre un error, devuelve un mensaje de error
            return $this->apiError($e->getMessage());
        }
    }
}
