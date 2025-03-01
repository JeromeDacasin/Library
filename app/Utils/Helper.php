<?php

namespace App\Utils;

class Helper
{
    public static function toBoolean($value) : bool 
    {
        return filter_var($value, FILTER_VALIDATE_BOOL);
    }
}