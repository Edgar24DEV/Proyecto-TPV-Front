<?php

namespace Controllers\Employee;

use App\Application\Employee\DTO\ListEmployeesRestaurantCommand;
use App\Application\Employee\Handlers\ListEmployeesRestaurantCommandHandler;
use Illuminate\Http\Request;
use App\Application\Employee\UseCases\ListEmployeesRestaurantUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class GetListEmployeesRestaurantController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly ListEmployeesRestaurantUseCase $listEmployeesRestaurantUseCase,
        private readonly ListEmployeesRestaurantCommandHandler $listEmployeesRestaurantCommandHandler,
    ) {}

    public function __invoke(Request $request)
    {

        try {

            $idRestaurant = $request->input("id_restaurante");

            $this->isNanId($idRestaurant);

            $command = new ListEmployeesRestaurantCommand(
                $idRestaurant
            );

            $result = $this->listEmployeesRestaurantCommandHandler->handle($command);


            return response()->json($result);
        } catch (\Exception $e) {
            Log::channel('employee')->error("Error listando empleados del restaurante ID: {$command->getIdRestaurant()}\n" .
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
