<?php

namespace Controllers\Employee;

use App\Application\Employee\DTO\FindByIdEmployeeCommand;
use App\Application\Employee\Handlers\FindByIdEmployeeCommandHandler;
use App\Application\Employee\UseCases\FindByIdEmployeeUseCase;
use Illuminate\Http\Request;
use App\Application\Employee\UseCases\ListEmployeesRestaurantUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class GetEmployeeController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly FindByIdEmployeeUseCase $findByIdEmployeeUseCase,
        private readonly FindByIdEmployeeCommandHandler $findByIdEmployeeCommandHandler,
    ) {

    }

    public function __invoke(Request $request)
    {

        try {

            $idEmployee = new FindByIdEmployeeCommand(
                $request->input("id"),
            );

            $this->isNanId($idEmployee->getId());

            $result = $this->findByIdEmployeeCommandHandler->handle($idEmployee);


            return response()->json($result);

        } catch (\Exception $e) {
            Log::channel('employee')->error("Error  accediendo al empleado ID: {$request->input("id")}\n" .
            "   Clase: " . __CLASS__ . "\n" .
            "   Mensaje: " . $e->getMessage() . "\n" .
            "   Línea: " . $e->getLine() . "\n" .
            "   Trace:\n" . collect($e->getTrace())
            ->take(3)
            ->map(function ($trace, $i) {
                return "    #$i " . ($trace['file'] ?? '') . ':' . ($trace['line'] ?? '') . ' → ' . ($trace['function'] ?? '');
            })
            ->implode("\n") . "\n");
            return $this->apiError("" . $e->getMessage());
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
