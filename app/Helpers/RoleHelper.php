<?php

namespace App\Helpers;

use App\Models\Role;

if (!function_exists('roles')) {
    function roles($id): string
    {
        $role = Role::find($id);
        return $role ? $role->name : 'Role Not Found';
    }
}