<?php

namespace Omadonex\LaravelTools\Support\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

trait JsonResponseTrait
{
    public function defaultJsonResponse(array|JsonResource $data = [], array $noty = []): JsonResponse
    {
        return response()->json([
            'status' => true,
            'noty'   => $noty,
            'result' => $data,
        ]);
    }
}
