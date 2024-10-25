<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Department;
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
            'name'       => fake()->sentence(3),
            'edition'    => fake()->text(100),
            'price'      => fake()->randomFloat(2, 100, 20000),
            'quantity'   => fake()->numberBetween(5, 77),
            'status'     => true,
            'author_id'  => Author::inRandomOrder()->first(),
            'department_id'  => Department::inRandomOrder()->first(),

        ];
    }
}
