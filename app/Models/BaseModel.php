<?php

namespace App\Models;

use App\Models\User as Authenticable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class BaseModel extends Authenticable
{
    use HasFactory, SoftDeletes, Notifiable, HasApiTokens;

    protected $guarded = ['id'];

    public function serializeDate(DateTimeInterface $date) : string 
    {
        return $date->format('Y-m-d H:i:s');
    }
}