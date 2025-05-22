<?php

namespace Controllers\Employee;

use App\Application\Employee\DTO\AddEmployeeCommand;
use App\Application\Employee\DTO\AddEmployeeRestaurantCommand;
use App\Application\Employee\Handlers\AddEmployeeCommandHandler;
use App\Application\Employee\Handlers\AddEmployeeRestaurantCommandHandler;
use App\Application\Employee\UseCases\AddEmployeeRestaurantUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\Employee\UseCases\AddEmployeeUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class PostEmployeeRestaurantController
{
    use ApiResponseTrait;


    public function __construct(
        private readonly AddEmployeeRestaurantUseCase $addEmployeeRestaurantUseCase,
        private readonly AddEmployeeRestaurantCommandHandler $addEmployeeRestaurantCommandHandler,
    ) {

    }


    public function __invoke(Request $request): JsonResponse
    {
        try {

            $this->validateOrFail($request);
            $command = new AddEmployeeRestaurantCommand(
                $request->get("id_empleado"),
                $request->get("id_restaurante"),
            );

            $result = $this->addEmployeeRestaurantCommandHandler->handle($command);
            return response()->json($result);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('employee')->error("Error añadiendo empleado {$command->getIdEmpleado()} al restaurante {$command->getIdRestaurante()}  \n" .
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
                'error' => 'La validación falló.',
                'messages' => $e->errors()
            ], 422);
        }
    }

    private function validateOrFail($request): void
    {
        $request->validate([
            "id_empleado" => "required|integer|exists:empleados,id",
            "id_restaurante" => "required|integer|exists:restaurantes,id",
        ]);
    }
}