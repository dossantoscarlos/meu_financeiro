<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Despesa;
use App\Models\Plano;
use App\Models\TipoDespesa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Despesa>
 */
class DespesaFactory extends Factory
{
    protected $model = Despesa::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status_despesa_id' => $this->faker->randomElement([1, 2, 3]),
            'plano_id' => Plano::factory(),
            'tipo_despesa_id' => TipoDespesa::factory(),
            'descricao' => $this->faker->sentence(),
            'valor_documento' => $this->faker->randomFloat(2, 10, 1000),
            'data_vencimento' => $this->faker->date(),
        ];
    }
}
