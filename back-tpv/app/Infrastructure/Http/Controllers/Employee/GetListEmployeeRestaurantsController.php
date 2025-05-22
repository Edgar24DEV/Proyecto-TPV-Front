<?php

namespace Controllers\Employee;

use App\Application\Employee\DTO\ListEmployeeRestaurantsCommand;
use App\Application\Employee\Handlers\ListEmployeeRestaurantsCommandHandler;
use Illuminate\Http\Request;
use App\Application\Employee\UseCases\ListEmployeeRestaurantsUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class GetListEmployeeRestaurantsController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly ListEmployeeRestaurantsUseCase $listEmployeeRestaurantsUseCase,
        private readonly ListEmployeeRestaurantsCommandHandler $listEmployeeRestaurantsCommandHandler,
    ) {

    }

    public function __invoke(Request $request)
    {

        try {

            $idCompany = $request->input("id_empresa");
            $idEmployee = $request->input("id_empleado");

            $this->isNanId($idCompany, $idEmployee);

            $command = new ListEmployeeRestaurantsCommand(
                $idCompany, 
                $idEmployee,
            );

            $result = $this->listEmployeeRestaurantsCommandHandler->handle($command);


            return response()->json($result);

        } catch (\Exception $e) {
            Log::channel('employee')->error("Error listando empleados \n" .
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

    private function isNanId($idCompany, $idEmployee)
    {
        if ($idCompany === null || !is_numeric($idCompany)) {
            throw new \InvalidArgumentException("El ID de compañia no es un número");
        }
        if ($idEmployee === null || !is_numeric($idEmployee)) {
            throw new \InvalidArgumentException("El ID del empleado no es un número");
        }
    }
}
