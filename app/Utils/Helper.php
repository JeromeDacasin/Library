<?php

namespace App\Utils;

use App\Models\BorrowedLimit;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class Helper
{
    public static function toBoolean($value) : bool 
    {
        return filter_var($value, FILTER_VALIDATE_BOOL);
    }

    public static function roleWithDisable($model) 
    {
        if ($model === 'BorrowLimit') 
        {
            $table = 'borrowed_limits';
        }

        $roles = DB::table('roles')->get();

        $usedRole = DB::table($table)->pluck('role_id')->toArray();

        return $roles->map(function ($role) use ($usedRole) {
            return [
                'id'       => $role->id,
                'name'     => $role->name,
                'disabled' => in_array($role->id, $usedRole)
            ];
        });
    }
}