<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plano>
 */
class PlanoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'mes_ano' => $this->faker->regexify('[0-1][0-9]/202[0-9]'),
            'descricao_simples' => $this->faker->sentence(),
        ];
    }
}
