<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use RuntimeException;

class CoffeeMachine
{
    private const STATE_KEY = 'coffee_machine_state';
    private const COFFEE_RECIPES = [
        'espresso' => ['name' => 'Espresso', 'coffee' => 8.0, 'water' => 24.0],
        'double_espresso' => ['name' => 'Double Espresso', 'coffee' => 16.0, 'water' => 48.0],
        'ristretto' => ['name' => 'Ristretto', 'coffee' => 8.0, 'water' => 16.0],
        'americano' => ['name' => 'Americano', 'coffee' => 16.0, 'water' => 148.0],
    ];

    private WaterContainer $waterContainer;
    private CoffeeContainer $coffeeContainer;

    public function __construct()
    {
        $this->loadState();
    }

    private function loadState(): void
    {
        $state = Cache::get(self::STATE_KEY);

        if ($state) {
            $this->waterContainer = new WaterContainer(
                $state['water']['capacity'],
                $state['water']['current']
            );
            $this->coffeeContainer = new CoffeeContainer(
                $state['coffee']['capacity'],
                $state['coffee']['current']
            );
        } else {
            $this->waterContainer = new WaterContainer();
            $this->coffeeContainer = new CoffeeContainer();
            $this->saveState();
        }
    }

    private function saveState(): void
    {
        Cache::put(self::STATE_KEY, [
            'water' => [
                'current' => $this->waterContainer->get(),
                'capacity' => $this->waterContainer->getCapacity(),
            ],
            'coffee' => [
                'current' => $this->coffeeContainer->get(),
                'capacity' => $this->coffeeContainer->getCapacity(),
            ],
        ]);
    }

    public function makeCoffee(string $type): array
    {
        if (!isset(self::COFFEE_RECIPES[$type])) {
            throw new RuntimeException("Unknown coffee type: {$type}");
        }

        $recipe = self::COFFEE_RECIPES[$type];

        // Check both containers before using either
        if ($this->waterContainer->get() < $recipe['water']) {
            throw new RuntimeException(
                sprintf(
                    "Not enough water to make %s! Need %.0f ml but only %.0f ml remaining.",
                    $recipe['name'],
                    $recipe['water'],
                    $this->waterContainer->get()
                )
            );
        }

        if ($this->coffeeContainer->get() < $recipe['coffee']) {
            throw new RuntimeException(
                sprintf(
                    "Not enough coffee to make %s! Need %.1f g but only %.1f g remaining.",
                    $recipe['name'],
                    $recipe['coffee'],
                    $this->coffeeContainer->get()
                )
            );
        }

        $this->waterContainer->use($recipe['water']);
        $this->coffeeContainer->use($recipe['coffee']);
        $this->saveState();

        return [
            'message' => "Your {$recipe['name']} is ready!",
            'used' => [
                'water' => $recipe['water'],
                'coffee' => $recipe['coffee'],
            ],
            'status' => $this->getStatus(),
        ];
    }

    public function fillWater(float $amount): array
    {
        $this->waterContainer->add($amount);
        $this->saveState();

        return [
            'message' => sprintf("Added %.0f ml of water. Water container now has %.0f ml.", $amount, $this->waterContainer->get()),
            'status' => $this->getStatus(),
        ];
    }

    public function fillCoffee(float $amount): array
    {
        $this->coffeeContainer->add($amount);
        $this->saveState();

        return [
            'message' => sprintf("Added %.1f g of coffee. Coffee container now has %.1f g.", $amount, $this->coffeeContainer->get()),
            'status' => $this->getStatus(),
        ];
    }

    public function getStatus(): array
    {
        return [
            'water' => [
                'current_ml' => round($this->waterContainer->get(), 1),
                'capacity_ml' => $this->waterContainer->getCapacity(),
                'percentage' => round($this->waterContainer->getPercentage(), 1),
            ],
            'coffee' => [
                'current_g' => round($this->coffeeContainer->get(), 1),
                'capacity_g' => $this->coffeeContainer->getCapacity(),
                'percentage' => round($this->coffeeContainer->getPercentage(), 1),
            ],
            'can_make' => $this->getAvailableRecipes(),
        ];
    }

    private function getAvailableRecipes(): array
    {
        $available = [];
        foreach (self::COFFEE_RECIPES as $type => $recipe) {
            $canMake = (
                $this->waterContainer->get() >= $recipe['water'] &&
                $this->coffeeContainer->get() >= $recipe['coffee']
            );

            $available[$type] = $canMake;
        }
        return $available;
    }
}
