<?php

namespace App\Http\Controllers;

use App\Http\Requests\FillCoffeeRequest;
use App\Http\Requests\FillWaterRequest;
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
            return response()->json($this->machine->getStatus());
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function brew(MakeCoffeeRequest $request): JsonResponse
    {
        try {
            $type = $request->validated()['type'];
            $coffee = $this->machine->makeCoffee($type);
            return response()->json($coffee);
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function fillWater(FillWaterRequest $request): JsonResponse
    {
        try {
            $amount = (float) $request->input('amount');

            $result = $this->machine->fillWater($amount);
            return response()->json($result);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Invalid amount. Please enter a value between 1 and 2000 ml.', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function fillCoffee(FillCoffeeRequest $request): JsonResponse
    {
        try {
            $amount = (float) $request->input('amount');

            $result = $this->machine->fillCoffee($amount);
            return response()->json($result);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Invalid amount. Please enter a value between 1 and 500 g.', Response::HTTP_UNPROCESSABLE_ENTITY);
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