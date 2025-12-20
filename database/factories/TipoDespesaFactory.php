<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\TipoDespesa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TipoDespesa>
 */
class TipoDespesaFactory extends Factory
{
    protected $model = TipoDespesa::class;

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
