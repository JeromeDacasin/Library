<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserInformation>
 */
class UserInformationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       

        return [
            'first_name' => fake()->firstName(),
            'last_name'  => fake()->lastName(),
            'birth_date' => fake()->date(),
            'gender'     => fake()->randomElement(['male', 'female']),
            'email'      => fake()->unique()->safeEmail(),
            'contact_number' => $this->generatePHPNumber(),
            'student_number' => null,
            'user_id' => null
        ];
    }

    public function generatePHPNumber()
    {
        $prefixes = ['917', '912', '905', '929', '908', '915']; 
        $prefix = $this->faker->randomElement($prefixes);
        $number = $this->faker->numerify('#######'); 
        return '+63' . $prefix . $number;
    }

}
