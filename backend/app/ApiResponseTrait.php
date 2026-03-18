<?php

namespace App;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\JsonResource;

trait ApiResponseTrait
{
    protected function successResponse(
        mixed $data = null,
        ?string $message = null,
        array $meta = [],
        int $status = Response::HTTP_OK,
    ): JsonResponse {
        $payload = [];

        $payload['message'] = $message ?? 'Success!';

        if ($data !== null) {
            if ($data instanceof JsonResource) {
                $data = $data->resolve(request());
            } elseif ($data instanceof Arrayable) {
                $data = $data->toArray();
            }

            $payload['data'] = $data;
        } else {
            $payload['data'] = null;
        }

        if (!empty($meta)) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $status);
    }

    protected function errorResponse(
        string $message,
        int $status = Response::HTTP_INTERNAL_SERVER_ERROR,
    ): JsonResponse {
        return response()->json([
            'message' => $message,
            'data'  => null,
        ], $status);
    }
}
