<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInformation extends Model
{
    use HasFactory;

    protected $table = 'user_informations';

    protected $fillable = [
        'first_name',
        'last_name',
        'birth_date',
        'gender',
        'email',
        'contact_number',
        'student_number',
        'user_id',
        'is_generated_student_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
