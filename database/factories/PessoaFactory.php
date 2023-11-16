<?php

namespace Database\Factories;

use App\Models\Pessoa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pessoa>
 */
class PessoaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'apelido' => $this->faker->unique()->userName,
            'nome' => $this->faker->name,
            'data_nascimento' => $this->faker->date,
            'stack' => $this->faker->randomElements([
                'PHP',
                'Laravel',
                'JavaScript',
                'Vue',
                'React',
                'Angular',
                'Node'
            ]),
        ];
    }
}
