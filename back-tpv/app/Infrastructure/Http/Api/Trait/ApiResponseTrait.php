<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Api\Trait;

use App\Infrastructure\Http\Api\Dto\ApiErrorResponse;
use App\Infrastructure\Http\Api\Dto\ApiSuccessResponse;


trait ApiResponseTrait
{
    protected function apiError($message, $data = [], ?int $status = 400)
    {
        return (new ApiErrorResponse($message, $data, $status))();
    }

    protected function apiSuccess($data = [], ?int $status = 200, $extraHeaders = [], ?string $message = null)
    {
        return (new ApiSuccessResponse($data, $status, $extraHeaders, $message))();
    }

}