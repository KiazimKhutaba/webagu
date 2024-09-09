<?php

namespace App\Common\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    public function response(mixed $data, int $http_code = 200): JsonResponse
    {
        return response()->json(['result' => $data], $http_code);
    }
}