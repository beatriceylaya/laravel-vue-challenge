<?php

namespace App\Services;
use App\Contracts\Container;
use RuntimeException;

class CoffeeContainer implements Container
{
    private float $current;

    public function __construct(
        private readonly float $capacity = 500.0,
        float $initial = 500.0
    ) {
        $this->current = min($initial, $capacity);
    }

    public function add(float $quantity): void
    {
        if ($quantity <= 0) {
            throw new RuntimeException("Coffee quantity must be greater than 0.");
        }

        if ($this->current + $quantity > $this->capacity) {
            throw new RuntimeException("Coffee container overflow!");
        }
        $this->current += $quantity;
    }

    public function use(float $quantity): float
    {
        if ($this->current <= 0) {
            throw new RuntimeException("Coffee container is empty! Please refill before making coffee.");
        }

        if ($this->current < $quantity) {
            throw new RuntimeException("Not enough coffee! Add more.");
        }

        $this->current -= $quantity;
        return $this->current;
    }

    public function get(): float { return $this->current; }
    public function getCapacity(): float { return $this->capacity; }
    public function getPercentage(): float { return ($this->current / $this->capacity) * 100; }
}