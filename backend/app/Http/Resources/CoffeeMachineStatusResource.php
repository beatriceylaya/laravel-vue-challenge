<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CoffeeMachineStatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'water' => $this->resource['water'],
            'coffee' => $this->resource['coffee'],
            'can_make' => $this->resource['can_make'],
        ];
    }
}
