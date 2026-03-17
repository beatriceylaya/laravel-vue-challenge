<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakeCoffeeRequest;
use App\Services\CoffeeMachine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class CoffeeMachineController extends Controller
{
    public function __construct(private readonly CoffeeMachine $machine) {}

    public function status(): JsonResponse
    {
        try {
            return response()->json([
                'data' => $this->machine->getStatus()
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function brew(MakeCoffeeRequest $request): JsonResponse
    {
        try {
            $type = $request->validated()['type'];
            $coffee = $this->machine->makeCoffee($type);
            return response()->json([
                'data' => $coffee
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    private function errorResponse(string $message, int $status = 500)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ],  $status);
    }

}