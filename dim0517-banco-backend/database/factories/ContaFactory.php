<?php
namespace Database\Factories;
use App\Models\Conta;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conta>
 */
class ContaFactory extends Factory
{
    public function definition()
    {
        return [
            'conta' => fake()->unique()->randomNumber(5),
            'saldo' => number_format(fake()->randomFloat(2, -1000, 1000), 2, '.', ''),
            'tipo' => fake()->randomElement(['bonus', 'tradicional', 'poupanca']),
            'pontos' => fake()->randomNumber(2),
        ];
    }
}