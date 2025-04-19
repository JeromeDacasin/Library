<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Database\Factories\BorrowedBookApproveFactory;
use Database\Factories\BorrowedBookBorrowedFactory;
use Database\Factories\BorrowedBookDeniedFactory;
use Database\Factories\BorrowedBookReturnFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BorrowedBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BorrowedBookDeniedFactory::new()->count(20)->create();

        BorrowedBookApproveFactory::new()->count(20)->create()->each(function ($approved) {
            $requestDate    = fake()->dateTimeBetween('2024-01-01', now());
            $borrowedDate   = (clone $requestDate)->modify('+1 day');

            $approved->request_date = $requestDate;
            $approved->borrowed_date = $borrowedDate;
            $approved->save();
        });

        BorrowedBookBorrowedFactory::new()->count(20)->create()->each(function ($borrowed) {
            $requestDate    = fake()->dateTimeBetween('2024-01-01', now());
            $borrowedDate   = (clone $requestDate)->modify('+1 day');
            $mustReturnDate = $this->addWeekDays(clone $borrowedDate, 7);

            $borrowed->request_date = $requestDate;
            $borrowed->borrowed_date = $borrowedDate;
            $borrowed->must_return_date = $mustReturnDate;
            $borrowed->save();
        });

        BorrowedBookReturnFactory::new()->count(20)->create()->each(function ($borrowed) {
            $requestDate    = fake()->dateTimeBetween('2024-01-01', now());
            $borrowedDate   = (clone $requestDate)->modify('+1 day');
            $mustReturnDate = $this->addWeekDays(clone $borrowedDate, 7);

            $borrowed->request_date = $requestDate;
            $borrowed->borrowed_date = $borrowedDate;
            $borrowed->must_return_date = $mustReturnDate;
            $borrowed->returned_date = $mustReturnDate;
            $borrowed->save();
        });
    }

    private function addWeekDays($date, $days)
    {
        $count = 0;

        $date = Carbon::instance($date); 
        while ($count < $days) {
            $date->addDay();
            if ($date->isWeekday()) {
                $count++;
            }
        }
        return $date;
    }
}