<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
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

    public function scopeSearch($query, $search) : Builder
    {
        return $query->where(function ($builder) use ($search) {
           
            $builder->orWhereHas('book', function($builder) use ($search) {
                $builder->where('title', 'LIKE', "%{$search}%");
            });
            
            $builder->orWhereHas('user.userInformation', function ($query) use ($search) {
                $query->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%");
            });
        });
    }
}