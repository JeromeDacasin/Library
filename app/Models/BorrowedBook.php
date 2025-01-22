<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowedBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_date',
        'borrowed_date',
        'must_return_date',
        'returned_date',
        'total_penalty',
        'status',
        'book_id',
        'user_id'
    ];

    protected $casts = [
        'request_date'     => 'date:Y-m-d'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}