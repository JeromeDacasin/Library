<?php

namespace App\Services;

use App\Models\BorrowedBook;

class DashboardService
{
    public function __construct(private readonly BorrowedBook $borrowedBook)
    {
        
    }

    public function dashboardData()
    {
        $today = $this->reportsByDay();
        $month = $this->reportsByCurrentMonth();
        $year = $this->reportByCurrentYear();
        
        return response()->json([
            'today_report' => $today,
            'month_report' => $month,
            'year_report' => $year,
        ]);
    }   

    private function reportsByDay()
    {
        $now = now()->toDateString();

        return $this->borrowedBook::selectRaw("
            COUNT(CASE WHEN status = 'requested' AND DATE(request_date) = ? THEN 1 END) as pending,
            COUNT(CASE WHEN status = 'borrowed' AND DATE(borrowed_date) = ? THEN 1 END) as approve,
            COUNT(CASE WHEN status = 'returned' AND DATE(returned_date) = ? THEN 1 END) as returned,
            COUNT(CASE WHEN status = 'borrowed' AND DATE(must_return_date) = ? THEN 1 END) as due_today
        ", [$now, $now, $now, $now])->first();
        
    }

    private function reportsByCurrentMonth()
    {
        $month = now()->month;
        $year = now()->year;
        return $this->borrowedBook::selectRaw("
            COUNT(CASE WHEN status = 'requested' AND MONTH(request_date) = ? AND YEAR(request_date) = ? THEN 1 END) as pending,
            COUNT(CASE WHEN status = 'borrowed' AND MONTH(borrowed_date) = ? AND YEAR(borrowed_date) = ? THEN 1 END) as approve,
            COUNT(CASE WHEN status = 'returned' AND MONTH(returned_date) = ? AND YEAR(returned_date) = ? THEN 1 END) as returned,
            COUNT(CASE WHEN status = 'borrowed' AND MONTH(must_return_date) = ? AND YEAR(must_return_date) = ? THEN 1 END) as due_this_month
        ", [$month, $year, $month, $year, $month, $year, $month, $year])->first();
    }

    private function reportByCurrentYear()
    {
        $year = now()->year;
        return $this->borrowedBook::selectRaw("
            COUNT(CASE WHEN status = 'requested' AND YEAR(request_date) = ? THEN 1 END) as pending,
            COUNT(CASE WHEN status = 'borrowed' AND YEAR(borrowed_date) = ? THEN 1 END) as approve,
            COUNT(CASE WHEN status = 'returned' AND YEAR(returned_date) = ? THEN 1 END) as returned
        ", [$year, $year, $year])
        ->first();
        // COUNT(CASE WHEN status = 'borrowed' AND YEAR(must_return_date) = ? THEN 1 END) as due_today
    }
    
}