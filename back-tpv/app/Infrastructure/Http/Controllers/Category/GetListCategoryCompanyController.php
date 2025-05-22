<?php

namespace Controllers\Category;

use App\Application\Category\DTO\ListCategoryCommand;
use App\Application\Category\DTO\ListCategoryCompanyCommand;
use App\Application\Category\Handlers\ListCategoryCommandHandler;
use App\Application\Category\Handlers\ListCategoryCompanyCommandHandler;
use Illuminate\Http\Request;
use App\Application\Category\UseCases\ListCategoryUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class GetListCategoryCompanyController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly ListCategoryUseCase $listCategoryUseCase,
        private readonly ListCategoryCompanyCommandHandler $listCategoryCommandHandler,
    ) {

    }

    public function __invoke(Request $request)
    {

        try {

            $id = $request->input("id");

            $this->isNanId($id);

            $command = new ListCategoryCompanyCommand(
                $id
            );

            $result = $this->listCategoryCommandHandler->handle($command);


            return response()->json($result);

        } catch (\Exception $e) {
            Log::channel('product')->warning(
                "ID inválido para  listar categorías: {$request->input('id')} \n" .
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
