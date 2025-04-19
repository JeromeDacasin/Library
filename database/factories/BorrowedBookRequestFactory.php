<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BorrowedBookRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'request_date'     => fake()->dateTime('2024-01-01', now()),       
            'borrowed_date'    => null,
            'must_return_date' => null,
            'returned_date'      => null,
            'total_penalty'    => null,
            'status'  => 'requested',
            'book_id' => Book::inRandomOrder()->first(),
            'user_id' => User::inRandomOrder()->first(),
        ];  
    }
}
