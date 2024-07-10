<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Categoria>
 */
class CategoriaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'nome' => fake()->name(),
        'icms' => fake()->tollFreePhoneNumber(),
        ];
    }//pesquisar ainda
}

// 'nome' => fake()->name(),
// 'descricao' => fake()->tollFreePhoneNumber(),
// 'preco' => fake()->unique()->email(),
// 'qtd_estoque' => fake()->numberBetween(1,3),
// 'categoria_id' => fake()->text(200)