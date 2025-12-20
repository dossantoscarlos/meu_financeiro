<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produto>
 */
class ProdutoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'descricao_curta' => $this->faker->sentence(),
            'preco' => $this->faker->randomFloat(2, 1, 100),
            'quantidade' => $this->faker->numberBetween(1, 10),
            'tipo_medida' => $this->faker->randomElement(['unidade', 'kg']),
            'data_compra' => $this->faker->date(),
            'user_id' => \App\Models\User::factory(),
            'total' => 0, // Will be calculated by model cast/setter if applicable, but we can set it here too
        ];
    }
}
