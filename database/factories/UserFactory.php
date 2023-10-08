<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Model>
 */
class UserFactory extends Factory
{
    protected $model = User::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $last_name = $this->faker->lastName();
        return [
            //
            'first_name' => $this->faker->firstName(),
            'last_name' => $last_name,
            'email' => $this->faker->safeEmail(),
            'phone_number' => $this->faker->e164PhoneNumber(),
            'password' => Hash::make($last_name),
            'address' => $this->faker->streetAddress(),
            'card_number' => $this->faker->creditCardNumber('Visa', true, '-'),
            'card_pin' => $this->faker->randomNumber(6, true),
            'balance' => rand(1_000_000, 500_000_000),
        ];
    }
}
