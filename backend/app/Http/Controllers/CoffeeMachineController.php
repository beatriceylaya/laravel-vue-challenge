<?php

namespace App\Http\Controllers;

use App\Http\Requests\FillCoffeeRequest;
use App\Http\Requests\FillWaterRequest;
use App\Http\Requests\MakeCoffeeRequest;
use App\Http\Resources\CoffeeMachineStatusResource;
use App\Services\CoffeeMachine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Throwable;

class CoffeeMachineController extends Controller
{
    public function __construct(private readonly CoffeeMachine $machine) {}

    public function status(): JsonResponse
    {
        try {
            return $this->successResponse(
                data: new CoffeeMachineStatusResource($this->machine->getStatus())
            );
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function brew(MakeCoffeeRequest $request): JsonResponse
    {
        try {
            $result = $this->machine->makeCoffee($request->validated('type'));
            return $this->successResponse(
                message: $result['message'],
                data: new CoffeeMachineStatusResource($result['status']),
                meta: ['used' => $result['used']],
            );
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function fillWater(FillWaterRequest $request): JsonResponse
    {
        try {
            $result = $this->machine->fillWater((float) $request->validated('amount'));

            return $this->successResponse(
                message: $result['message'],
                data: new CoffeeMachineStatusResource($result['status']),
            );
        } catch (ValidationException $e) {
            return $this->errorResponse('Invalid amount. Please enter a value between 1 and 2000 ml.', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function fillCoffee(FillCoffeeRequest $request): JsonResponse
    {
        try {
            $result = $this->machine->fillCoffee((float) $request->validated('amount'));

            return $this->successResponse(
                message: $result['message'],
                data: new CoffeeMachineStatusResource($result['status']),
            );
        } catch (ValidationException $e) {
            return $this->errorResponse('Invalid amount. Please enter a value between 1 and 500 g.', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}