<?php

namespace App\Helpers;

use DateTime;

function formatNullableDate(?string $date) : ?string 
{
    return $date ? (new DateTime($date))->format('Y-m-d') : null;
}
