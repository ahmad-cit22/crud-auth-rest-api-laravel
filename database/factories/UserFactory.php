<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
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
        return [
            'name' => $this->faker->text(maxNbChars: 20),
            'email' => $this->faker->email(),
            'phone_number' => $this->faker->phoneNumber(),
            'password' => $this->faker->password(minLength: 8, maxLength: 16),
            'institution' => $this->faker->text(maxNbChars: 100),
            'city' => $this->faker->city(),
            'country' => $this->faker->country(),
        ];
    }
}
