<?php

namespace Controllers\Employee;

use App\Application\Employee\DTO\DeleteEmployeeCommand;
use App\Application\Employee\DTO\DeleteEmployeesCommand;
use App\Application\Employee\Handlers\DeleteEmployeeCommandHandler;
use App\Application\Employee\UseCases\DeleteEmployeeUseCase;
use App\Application\OrderLine\DTO\DeleteOrderLineCommand;
use App\Application\OrderLine\Handlers\DeleteOrderLineCommandHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\OrderLine\UseCases\DeleteOrderLineUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class DeleteEmployeesController
{
    use ApiResponseTrait;


    public function __construct(
        private readonly DeleteEmployeeUseCase $deleteEmployeesUseCase,
        private readonly DeleteEmployeeCommandHandler $deleteEmployeesCommandHandler,
    ) {}


    public function __invoke(Request $request): JsonResponse
    {

        $id = new DeleteEmployeeCommand(
            $request->input("id"),
        );

        try {
            $this->isNanId($id->getId());
            $success = $this->deleteEmployeesCommandHandler->handle($id);

            if ($success) {
                return response()->json("Empleado eliminado con éxito");
            }

            return response()->json([
                'error' => "No se pudo eliminar el empleado"
            ], 500);
        } catch (\InvalidArgumentException $e) {
            Log::channel('employee')->error("Error eliminando empleado ID: {$request->input("id")}\n" .
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
                'error' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            Log::channel('employee')->error("Error eliminando empleado ID: {$request->input("id")}\n" .
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
                'error' =>  $e->getMessage()
            ], 500);
        }
    }

    private function isNanId($id)
    {
        if ($id === null || !is_numeric($id) || $id <= 0) {
            throw new \InvalidArgumentException(
                $id <= 0 ? "El ID debe ser positivo" : "El ID debe ser numérico"
            );
        }
    }
}
