<?php

namespace Database\Factories;

use App\Models\Card;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Card>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $activationDate = fake()->dateTimeBetween('-6 months', 'now');

        return [
            'user_id' => User::factory(),
            'card_number' => fake()->numerify('####################'),
            'pin' => fake()->numerify('####'),
            'activation_date' => $activationDate,
            'expiration_date' => fake()->dateTimeBetween($activationDate, '+2 years')->format('Y-m-d'),
            'balance' => fake()->randomFloat(2, 0, 500),
        ];
    }
}
