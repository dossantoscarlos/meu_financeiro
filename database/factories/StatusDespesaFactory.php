<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\StatusDespesa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StatusDespesa>
 */
class StatusDespesaFactory extends Factory
{
    protected $model = StatusDespesa::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->unique()->word(),
        ];
    }
}
