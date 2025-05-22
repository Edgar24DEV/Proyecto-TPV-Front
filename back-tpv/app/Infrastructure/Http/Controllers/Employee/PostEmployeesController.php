<?php

namespace Controllers\Employee;

use App\Application\Employee\DTO\AddEmployeeCommand;
use App\Application\Employee\Handlers\AddEmployeeCommandHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\Employee\UseCases\AddEmployeeUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class PostEmployeesController
{
    use ApiResponseTrait;


    public function __construct(
        private readonly AddEmployeeUseCase $addEmployeesUseCase,
        private readonly AddEmployeeCommandHandler $addEmployeeCommandHandler,
    ) {

    }


    public function __invoke(Request $request): JsonResponse
    {
        try {

            $this->validateOrFail($request);
            $command = new AddEmployeeCommand(
                $request->get("nombre"),
                $request->get("pin"),
                $request->get("id_empresa"),
                $request->get("id_rol"),
                $request->get("id_restaurante"),
            );

            $result = $this->addEmployeeCommandHandler->handle($command);
            $this->validateOfUseCase($result);
            return response()->json($result);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('employee')->error("Error añadiendo empleado   \n" .
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
            "nombre" => "required|string|max:255",
            "pin" => "required|regex:/^\d{4}$/",
            "id_empresa" => "required|integer|exists:empresas,id",
            "id_rol" => "required|integer|exists:rols,id",
        ]);
    }

    private function validateOfUseCase($result): void
    {
        if ($result->id < 1) {
            $this->apiError("No se ha podido crear el empleado");
        }
    }
}