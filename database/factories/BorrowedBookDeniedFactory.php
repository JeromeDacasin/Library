<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\BorrowedBook;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BorrowedBookDeniedFactory extends Factory
{
    protected $model = BorrowedBook::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'request_date'     => fake()->dateTimeBetween('2024-01-01', now()),       
            'borrowed_date'    => null,
            'must_return_date' => null,
            'returned_date'      => null,
            'total_penalty'    => null,
            'status'  => 'denied',
            'book_id' => Book::inRandomOrder()->first(),
            'user_id' => User::inRandomOrder()->first(),
        ];  
    }
}
