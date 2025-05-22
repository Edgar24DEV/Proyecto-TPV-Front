<?php

namespace Controllers\Employee;

use App\Application\Employee\DTO\LoginEmployeeCommand;
use App\Application\Employee\Handlers\LoginEmployeeCommandHandler;
use App\Domain\Employee\Entities\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\Employee\UseCases\LoginEmployeeUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class GetLoginEmployeesController
{
    use ApiResponseTrait;


    public function __construct(
        private readonly LoginEmployeeUseCase $loginEmployeesUseCase,
        private readonly LoginEmployeeCommandHandler $loginEmployeeCommandHandler,
    ) {

    }


    public function __invoke(Request $request): JsonResponse
    {
        $id = $request->input("id");
        try {
            $this->isNanId($id);

            $command = new LoginEmployeeCommand(
                $request->input("id"),
                $request->input("pin"),
            );

            $result = $this->loginEmployeeCommandHandler->handle($command);

            if ($result->getId() === -1) {
                return response()->json([
                    'error' => 'No se pudo autenticar el empleado. Verifica tus credenciales.'
                ], 401);
            }

            return response()->json($result);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('employee')->error("Error logueando empleado: {$command->getId()}\n" .
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

    private function isNanId($id)
    {
        if ($id === null || !is_numeric($id)) {
            throw new \InvalidArgumentException("El ID no es un número");
        }
    }

}