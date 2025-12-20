<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Renda;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Renda>
 */
class RendaFactory extends Factory
{
    protected $model = Renda::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'saldo' => $this->faker->randomFloat(2, 1000, 5000),
            'custo' => $this->faker->randomFloat(2, 0, 1000),
            'user_id' => User::factory(),
        ];
    }
}
