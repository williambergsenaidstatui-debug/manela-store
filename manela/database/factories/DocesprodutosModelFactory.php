<?php

namespace Database\Factories;

use App\Models\DocesprodutosModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<DocesprodutosModel> */
class DocesprodutosModelFactory extends Factory
{
    protected $model = DocesprodutosModel::class;

    public function definition(): array
    {
        return [
            'categoria' => fake()->randomElement(['Bolos', 'Doces', 'Sobremesas']),
            'preco' => fake()->randomFloat(2, 5, 200),
            'nome' => fake()->words(3, true),
            'sobre' => fake()->sentence(),
            'descricao' => fake()->paragraph(),
            'imagem' => null,
            'id_produto' => null,
        ];
    }
}
