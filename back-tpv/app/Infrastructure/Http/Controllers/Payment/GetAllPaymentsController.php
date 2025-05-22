<?php

namespace Controllers\Payment;

use App\Application\Employee\DTO\FindByIdEmployeeCommand;
use App\Application\Employee\Handlers\FindByIdEmployeeCommandHandler;
use App\Application\Employee\UseCases\FindByIdEmployeeUseCase;
use App\Application\Payment\Handlers\ListAllPaymentsCommandHandler;
use App\Application\Payment\UseCases\ListAllPaymentsUseCase;
use App\Application\Payment\DTO\ListAllPaymentsCommand;
use Illuminate\Http\Request;
use App\Application\Employee\UseCases\ListEmployeesRestaurantUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class GetAllPaymentsController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly ListAllPaymentsUseCase $listAllPaymentsUseCase,
        private readonly ListAllPaymentsCommandHandler $listAllPaymentsCommandHandler,
    ) {

    }

    public function __invoke(Request $request)
    {

        $this->isNanId($request->input("id_restaurante"));

        try {

            $idRestaurant = new ListAllPaymentsCommand(
                $request->input("id_restaurante"),
            );

            $result = $this->listAllPaymentsCommandHandler->handle($idRestaurant);

            return response()->json($result);

        }catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('order')->warning(
                "La validación fallóal listar todos los pagos del restaurante {$request->input('id_ restaurante')}\n" .
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
        }catch (\InvalidArgumentException $e) {
            Log::channel('order')->warning(
                "Error en algún campo al listar todos los pagos del restaurante {$request->input('id_restaurante')}\n" .
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
        catch (\Exception $e) {
            Log::channel('order')->error(
                "Error al listar todos los pagos del restaurante {$request->input('id_restaurante')}\n"  .
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
