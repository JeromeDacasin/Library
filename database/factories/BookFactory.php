<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Department;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'       => fake()->sentence(3),
            'edition'     => fake()->text(100),
            'price'       => fake()->randomFloat(2, 100, 20000),
            'total_quantity'    => fake()->numberBetween(20, 77),
            'remaining'         => fake()->numberBetween(0, 19),
            'acquired_via'      => 'Donations',
            'is_active'         => true,
            'author_id'         => Author::inRandomOrder()->first(),
            'department_id'     => Department::inRandomOrder()->first(),
            'publisher_id'      => Publisher::inRandomOrder()->first(),
        ];
    }
}
