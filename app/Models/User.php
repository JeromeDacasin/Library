<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
        'is_active',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function userInformation()
    {
        return $this->hasOne(UserInformation::class);
    }

    public function scopeSearch($query, $search) : Builder
    {
        return $query->whereHas('userInformation', function ($builder) use ($search) {
            $builder->whereAny(['first_name', 'last_name', 'student_number'], 'LIKE', "{$search}%");
        });
        
    }

    public function scopeIsGenerated($query) : Builder
    {
        return $query->whereHas('userInformation', function ($builder)  {
            $builder->where('is_generated_student_number', 1);
        });
    }

    public function scopeSearchUsernameOrEmail($query, $search): Builder
    {
        return $query->where(function($builder) use ($search) {
            $builder->orWhere('username', 'LIKE', '%' . $search . '%')
                ->orWhereHas('userInformation', function ($q) use ($search) {
                    $q->where('email', 'LIKE', '%' . $search . '%');
                });
        }); 
    }
}
