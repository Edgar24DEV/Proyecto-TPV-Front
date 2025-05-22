<?php

namespace Controllers\Product;

use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;


class UpdateImageController
{
    use ApiResponseTrait;


    public function __invoke(Request $request)
    {
        if ($request->hasFile('imagen')) {
            $originalName = $request->file('imagen')->getClientOriginalName();
            $path = $request->file('imagen')->storeAs('', $originalName, 'public');

            return response()->json(['path' => $path]);
        }

        return response()->json(['error' => 'No se subió ninguna imagen'], 400);
    }


}