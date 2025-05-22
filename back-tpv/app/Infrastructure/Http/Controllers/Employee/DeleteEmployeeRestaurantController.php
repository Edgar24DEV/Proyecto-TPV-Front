<?php

namespace Controllers\Employee;


use App\Application\Employee\DTO\DeleteEmployeeRestaurantCommand;
use App\Application\Employee\Handlers\DeleteEmployeeRestaurantCommandHandler;
use App\Application\Employee\UseCases\DeleteEmployeeRestaurantUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class DeleteEmployeeRestaurantController
{
    use ApiResponseTrait;


    public function __construct(
        private readonly DeleteEmployeeRestaurantUseCase $deleteEmployeeRestaurantUseCase,
        private readonly DeleteEmployeeRestaurantCommandHandler $deleteEmployeeRestaurantCommandHandler,
    ) {}


    public function __invoke(Request $request): JsonResponse
    {

        $id = new DeleteEmployeeRestaurantCommand(
            $request->input("id_empleado"),
            $request->input("id_restaurante"),
        );

        try {
            $this->isNanId($id->getIdEmpleado(), $id->getIdRestaurante());
            $success = $this->deleteEmployeeRestaurantCommandHandler->handle($id);

            if ($success) {
                return response()->json("Relacion eliminada con éxito");
            }

            return response()->json([
                'error' => "No se pudo eliminar la relacion"
            ], 500);
        } catch (\InvalidArgumentException $e) {
            Log::channel('employee')->error("Error eliminando empleado ID: {$request->input("id_empleado")} del restaurante: {$request->input("id_restaurante")}\n" .
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
            Log::channel('employee')->error("Error eliminando empleado ID: {$request->input("id_empleado")} del restaurante: {$request->input("id_restaurante")}\n" .
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

    private function isNanId($idEmpleado, $idRestaurante)
    {
        if ($idEmpleado === null || !is_numeric($idEmpleado) || $idEmpleado <= 0) {
            throw new \InvalidArgumentException(
                $idEmpleado <= 0 ? "El ID del empleado debe ser positivo" : "El ID del empleado debe ser numérico"
            );
        }
        if ($idRestaurante === null || !is_numeric($idRestaurante) || $idRestaurante <= 0) {
            throw new \InvalidArgumentException(
                $idEmpleado <= 0 ? "El ID del restaurante debe ser positivo" : "El ID del restaurante debe ser numérico"
            );
        }
    }
}
