<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowedLimit extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function role() 
    {
        return $this->belongsTo(Role::class);
    }
}
