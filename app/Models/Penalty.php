<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    use HasFactory;

    protected $fillable = [
        'fine',
        'role_id'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
