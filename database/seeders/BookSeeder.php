<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::factory(50)
            ->create();
    }
}
