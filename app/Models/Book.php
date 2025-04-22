<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class)->withTrashed();
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->whereAny([
                    'id',
                    'acquisition_id',
                    'edition',
                    'title'
                ], 'LIKE',  $search . '%');

     
            $q->orWhereHas('department', function($q) use($search) {
                $q->where('name', 'LIKE', $search .'%');
            });

            $q->orWhereHas('author', function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                ->orWhere('last_name', 'LIKE', "%{$search}%");
            });
        });
    }
}
